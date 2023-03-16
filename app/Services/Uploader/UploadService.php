<?php

namespace App\Services\Uploader;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\{MessageBag, Str};
use Illuminate\Support\Facades\{Storage, Validator};
use App\Services\Uploader\Classes\ThumbConfig;
use App\Services\Uploader\Helpers\ImageHelper;
use App\Services\Uploader\Models\Mediafile;

class UploadService
{
    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_UPDATE = 'update';

    const FILE_TYPE_IMAGE = 'image';
    const FILE_TYPE_AUDIO = 'audio';
    const FILE_TYPE_VIDEO = 'video';
    const FILE_TYPE_APP = 'application';
    const FILE_TYPE_APP_WORD = 'word';
    const FILE_TYPE_APP_EXCEL = 'excel';
    const FILE_TYPE_APP_PDF = 'pdf';
    const FILE_TYPE_TEXT = 'text';
    const FILE_TYPE_OTHER = 'other';
    const FILE_TYPE_THUMB = 'thumbnail';

    const THUMB_ALIAS_DEFAULT  = 'default';
    const THUMB_ALIAS_ORIGINAL = 'original';
    const THUMB_ALIAS_SMALL    = 'small';
    const THUMB_ALIAS_MEDIUM   = 'medium';
    const THUMB_ALIAS_LARGE    = 'large';

    const DIR_LENGTH_FIRST = 2;
    const DIR_LENGTH_SECOND = 4;

    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var bool
     */
    private $renameFiles;

    /**
     * @var bool
     */
    private $checkExtensionByMimeType;

    /**
     * @var int
     */
    private $fileMaxSize;

    /**
     * @var array
     */
    private $fileExtensions;

    /**
     * @var array
     */
    private $thumbSizes;

    /**
     * @var string
     */
    private $thumbFilenameTemplate;

    /**
     * @var array
     */
    private $uploadDirectories;


    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $uploadDirectory;

    /**
     * @var string
     */
    private $directoryForDelete;

    /**
     * @var string
     */
    private $outFileName;

    /**
     * @var string
     */
    private $databaseUrl;

    /**
     * @var string
     */
    private $scenario;

    /**
     * @var Mediafile
     */
    private $mediafileModel;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var MessageBag
     */
    private $errors;


    public static function getInstance(array $config): self
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }


    /************************* CONFIG SETTERS ****************************/
    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param bool $renameFiles
     * @return $this
     */
    public function setRenameFiles(bool $renameFiles): self
    {
        $this->renameFiles = $renameFiles;
        return $this;
    }

    /**
     * @param bool $checkExtensionByMimeType
     * @return $this
     */
    public function setCheckExtensionByMimeType(bool $checkExtensionByMimeType): self
    {
        $this->checkExtensionByMimeType = $checkExtensionByMimeType;
        return $this;
    }

    /**
     * @param int $fileMaxSize
     * @return $this
     */
    public function setFileMaxSize(int $fileMaxSize): self
    {
        $this->fileMaxSize = $fileMaxSize;
        return $this;
    }

    /**
     * @param array $fileExtensions
     * @return $this
     */
    public function setFileExtensions(array $fileExtensions): self
    {
        $this->fileExtensions = $fileExtensions;
        return $this;
    }

    /**
     * @param array $thumbSizes
     * @return $this
     */
    public function setThumbSizes(array $thumbSizes): self
    {
        $this->thumbSizes = $thumbSizes;
        return $this;
    }

    /**
     * @param string $thumbFilenameTemplate
     * @return $this
     */
    public function setThumbFilenameTemplate(string $thumbFilenameTemplate): self
    {
        $this->thumbFilenameTemplate = $thumbFilenameTemplate;
        return $this;
    }

    /**
     * @param array $uploadDirectories
     * @return $this
     */
    public function setUploadDirectories(array $uploadDirectories): self
    {
        $this->uploadDirectories = $uploadDirectories;
        return $this;
    }


    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param Mediafile $model
     * @return $this
     */
    public function setMediafileModel(Mediafile $model)
    {
        $this->mediafileModel = $model;
        return $this;
    }

    /**
     * @return Mediafile
     */
    public function getMediafileModel(): Mediafile
    {
        return $this->mediafileModel;
    }

    /**
     * @param UploadedFile|null $file
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        if (empty($this->mediafileModel->getKey())) {
            $this->scenario = self::SCENARIO_UPLOAD;
        } else {
            $this->scenario = self::SCENARIO_UPDATE;
        }

        if (!$this->validate()) {
            return false;
        }

        if (null !== $this->file) {

            $this->setParamsForSend();

            if (!$this->sendFile()) {
                throw new \Exception('Error upload file.');
            }

            if ($this->scenario == self::SCENARIO_UPDATE) {
                $this->setParamsForDelete();
                $this->deleteFiles();
            }

            $this->mediafileModel->url = $this->databaseUrl;
            $this->mediafileModel->filename = $this->outFileName;
            $this->mediafileModel->size = $this->file->getSize();
            $this->mediafileModel->type = $this->file->getMimeType();
            $this->mediafileModel->disk = Storage::getDefaultDriver();
            $this->mediafileModel->driver = Storage::getConfig()['driver'];
        }

        $this->mediafileModel->alt = $this->data['alt'];
        $this->mediafileModel->title = $this->data['title'];
        $this->mediafileModel->description = $this->data['description'];

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }

        $this->afterSave();

        return true;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function delete(): int
    {
        $this->setParamsForDelete();

        $this->deleteFiles();

        $deleted = $this->mediafileModel->delete();

        if (false === $deleted) {
            throw new \Exception('Error delete file data from database.', 500);
        }

        return $deleted;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->mediafileModel->id;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validator = Validator::make($this->data, $this->validateRules($this->scenario));
        if ($validator->fails()) {
            $this->errors = $validator->getMessageBag();
            return false;
        }
        return true;
    }

    /**
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return $this->errors;
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function createThumbs(): bool
    {
        $thumbs = [];

        ImageHelper::$driver = [ImageHelper::DRIVER_GD2, ImageHelper::DRIVER_GMAGICK, ImageHelper::DRIVER_IMAGICK];

        foreach ($this->thumbSizes as $alias => $preset) {
            $thumbUrl = $this->createThumb(Module::configureThumb($alias, $preset));
            if (null === $thumbUrl) {
                continue;
            }
            $thumbs[$alias] = $thumbUrl;
        }

        // Create default thumb.
        if (!array_key_exists(self::THUMB_ALIAS_DEFAULT, $this->thumbSizes)) {
            $thumbUrlDefault = $this->createThumb(
                Module::configureThumb(
                    self::THUMB_ALIAS_DEFAULT,
                    Module::getDefaultThumbConfig()
                )
            );
            if (null !== $thumbUrlDefault) {
                $thumbs[self::THUMB_ALIAS_DEFAULT] = $thumbUrlDefault;
            }
        }

        $this->mediafileModel->thumbs = serialize($thumbs);

        return $this->mediafileModel->save();
    }


    /********************** PROCESS INTERNAL METHODS *********************/
    /**
     * @param ThumbConfig $thumbConfig
     * @return string
     */
    private function createThumb(ThumbConfig $thumbConfig)
    {
        $originalPathInfo = pathinfo($this->mediafileModel->url);

        $thumbPath = $originalPathInfo['dirname'] .
            DIRECTORY_SEPARATOR .
            $this->getThumbFilename($originalPathInfo['filename'],
                $originalPathInfo['extension'],
                $thumbConfig->getAlias(),
                $thumbConfig->getWidth(),
                $thumbConfig->getHeight()
            );

        $thumbContent = ImageHelper::thumbnail(Storage::path($this->mediafileModel->url),
            $thumbConfig->getWidth(),
            $thumbConfig->getHeight(),
            $thumbConfig->getMode()
        )->get($originalPathInfo['extension']);

        Storage::put($thumbPath, $thumbContent);

        return $thumbPath;
    }

    /**
     * @return bool
     */
    private function sendFile(): bool
    {
        Storage::putFileAs($this->uploadDirectory, $this->file, $this->outFileName);

        return Storage::fileExists($this->uploadDirectory . DIRECTORY_SEPARATOR . $this->outFileName);
    }

    /**
     * Delete local directory with original file and thumbs.
     * @return bool
     */
    private function deleteFiles(): bool
    {
        return Storage::deleteDirectory($this->directoryForDelete);
    }

    /**
     * @param string $scenario
     * @return array
     */
    private function validateRules(string $scenario): array
    {
        switch ($scenario) {
            case self::SCENARIO_UPLOAD:
                return [

                ];
            case self::SCENARIO_UPDATE:
                return [

                ];
            default:
                return [];
        }
    }

    /**
     * @throws \Exception
     * @return void
     */
    private function setParamsForSend(): void
    {
        $uploadDirectory = rtrim(rtrim($this->getUploadDirConfig($this->file->getMimeType()), '/'), '\\');

        if (!empty($this->data['subDir'])) {
            $uploadDirectory = $uploadDirectory . DIRECTORY_SEPARATOR . trim(trim($this->data['subDir'], '/'), '\\');
        }

        $this->uploadDirectory = $uploadDirectory .
            DIRECTORY_SEPARATOR . substr(md5(time()), 0, self::DIR_LENGTH_FIRST) .
            DIRECTORY_SEPARATOR . substr(md5(microtime() . $this->file->getBasename()), 0, self::DIR_LENGTH_SECOND);

        $this->outFileName = $this->renameFiles ?
            Str::uuid() . '.' . $this->file->getExtension() :
            Str::slug($this->file->getBasename()) . '.' . $this->file->getExtension();

        $this->databaseUrl = $this->uploadDirectory . DIRECTORY_SEPARATOR . $this->outFileName;
    }

    /**
     * @return void
     */
    private function setParamsForDelete(): void
    {
        $originalPathinfo = pathinfo($this->mediafileModel->getUrl());

        $dirnameParent = substr($originalPathinfo['dirname'], 0, -(self::DIR_LENGTH_SECOND + 1));
        $childDirectories = Storage::disk($this->mediafileModel->getDisk())->directories($dirnameParent);

        $this->directoryForDelete = count($childDirectories) == 1
            ? $dirnameParent
            : $originalPathinfo['dirname'];
    }

    /**
     * @param $original
     * @param $extension
     * @param $alias
     * @param $width
     * @param $height
     * @return string
     */
    private function getThumbFilename($original, $extension, $alias, $width, $height)
    {
        return strtr($this->thumbFilenameTemplate, [
            '{original}'  => $original,
            '{extension}' => $extension,
            '{alias}'     => $alias,
            '{width}'     => $width,
            '{height}'    => $height,
        ]);
    }

    /**
     * @param string $fileType
     * @throws Exception
     * @return string
     */
    private function getUploadDirConfig(string $fileType): string
    {
        if (!is_array($this->uploadDirectories) || empty($this->uploadDirectories)) {
            throw new Exception('The localUploadDirs is not defined.');
        }

        if (str_contains($fileType, self::FILE_TYPE_IMAGE)) {
            return $this->uploadDirectories[self::FILE_TYPE_IMAGE];

        } elseif (str_contains($fileType, self::FILE_TYPE_AUDIO)) {
            return $this->uploadDirectories[self::FILE_TYPE_AUDIO];

        } elseif (str_contains($fileType, self::FILE_TYPE_VIDEO)) {
            return $this->uploadDirectories[self::FILE_TYPE_VIDEO];

        } elseif (str_contains($fileType, self::FILE_TYPE_APP)) {
            return $this->uploadDirectories[self::FILE_TYPE_APP];

        } elseif (str_contains($fileType, self::FILE_TYPE_TEXT)) {
            return $this->uploadDirectories[self::FILE_TYPE_TEXT];

        } else {
            return $this->uploadDirectories[self::FILE_TYPE_OTHER];
        }
    }

    /**
     * @return mixed
     */
    private function afterSave()
    {
        if (!empty($this->data['owner_id']) && !empty($this->data['owner']) && !empty($this->data['owner_attribute'])) {
            $this->mediafileModel->addOwner($this->data['owner_id'], $this->data['owner'], $this->data['owner_attribute']);
        }
    }
}
