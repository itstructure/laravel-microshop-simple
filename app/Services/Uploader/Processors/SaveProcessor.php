<?php

namespace App\Services\Uploader\Processors;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{
    Storage, Validator
};
use App\Services\Uploader\Classes\ThumbConfig;
use App\Services\Uploader\Helpers\{
    ImageHelper, ThumbHelper
};

abstract class SaveProcessor extends BaseProcessor
{
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

    const THUMB_ALIAS_DEFAULT = 'default';
    const THUMB_ALIAS_ORIGINAL = 'original';
    const THUMB_ALIAS_SMALL = 'small';
    const THUMB_ALIAS_MEDIUM = 'medium';
    const THUMB_ALIAS_LARGE = 'large';

    const DIR_LENGTH_FIRST = 2;
    const DIR_LENGTH_SECOND = 4;

    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var bool
     */
    protected $renameFiles;

    /**
     * @var bool
     */
    protected $checkExtensionByMimeType;

    /**
     * @var int
     */
    protected $fileMaxSize;

    /**
     * @var array
     */
    protected $fileExtensions;

    /**
     * @var array
     */
    protected $thumbSizes;

    /**
     * @var string
     */
    protected $thumbFilenameTemplate;

    /**
     * @var array
     */
    protected $uploadDirectories;


    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $outFileName;

    /**
     * @var string
     */
    protected $databaseUrl;


    /************************* ABSTRACT METHODS ***************************/
    abstract protected function getValidateRules(): array;


    /************************* CONFIG SETTERS ****************************/
    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param bool $renameFiles
     * @return $this
     */
    public function setRenameFiles(bool $renameFiles)
    {
        $this->renameFiles = $renameFiles;
        return $this;
    }

    /**
     * @param bool $checkExtensionByMimeType
     * @return $this
     */
    public function setCheckExtensionByMimeType(bool $checkExtensionByMimeType)
    {
        $this->checkExtensionByMimeType = $checkExtensionByMimeType;
        return $this;
    }

    /**
     * @param int $fileMaxSize
     * @return $this
     */
    public function setFileMaxSize(int $fileMaxSize)
    {
        $this->fileMaxSize = $fileMaxSize;
        return $this;
    }

    /**
     * @param array $fileExtensions
     * @return $this
     */
    public function setFileExtensions(array $fileExtensions)
    {
        $this->fileExtensions = $fileExtensions;
        return $this;
    }

    /**
     * @param array $thumbSizes
     * @return $this
     */
    public function setThumbSizes(array $thumbSizes)
    {
        $this->thumbSizes = $thumbSizes;
        return $this;
    }

    /**
     * @param string $thumbFilenameTemplate
     * @return $this
     */
    public function setThumbFilenameTemplate(string $thumbFilenameTemplate)
    {
        $this->thumbFilenameTemplate = $thumbFilenameTemplate;
        return $this;
    }

    /**
     * @param array $uploadDirectories
     * @return $this
     */
    public function setUploadDirectories(array $uploadDirectories)
    {
        $this->uploadDirectories = $uploadDirectories;
        return $this;
    }


    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @throws Exception
     * @return bool
     */
    public function run(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        return parent::run();
    }

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
     * @throws Exception
     * @return bool
     */
    public function createThumbs(): bool
    {
        $thumbs = [];

        ImageHelper::$driver = [ImageHelper::DRIVER_GD2, ImageHelper::DRIVER_GMAGICK, ImageHelper::DRIVER_IMAGICK];

        foreach ($this->thumbSizes as $alias => $preset) {
            $thumbs[$alias] = $this->createThumb(ThumbHelper::configureThumb($alias, $preset));
        }

        // Create default thumb.
        if (!array_key_exists(self::THUMB_ALIAS_DEFAULT, $this->thumbSizes)) {
            $defaultThumbConfig = ThumbHelper::configureThumb(self::THUMB_ALIAS_DEFAULT, ThumbHelper::getDefaultSizes());
            $thumbs[self::THUMB_ALIAS_DEFAULT] = $this->createThumb($defaultThumbConfig);
        }

        $this->mediafileModel->thumbs = $thumbs;

        return $this->mediafileModel->save();
    }


    /********************** PROCESS INTERNAL METHODS *********************/
    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $validator = Validator::make($this->data, $this->getValidateRules());
        if ($validator->fails()) {
            $this->errors = $validator->getMessageBag();
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function sendFile(): bool
    {
        Storage::disk($this->currentDisk)->putFileAs($this->processDirectory, $this->file, $this->outFileName);

        return Storage::disk($this->currentDisk)->fileExists($this->processDirectory . DIRECTORY_SEPARATOR . $this->outFileName);
    }

    /**
     * @param string $fileType
     * @throws Exception
     * @return string
     */
    protected function getUploadDirConfig(string $fileType): string
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

    protected function getNewProcessDirectory(): string
    {
        $processDirectory = rtrim(rtrim($this->getUploadDirConfig($this->file->getMimeType()), '/'), '\\');

        if (!empty($this->data['subDir'])) {
            $processDirectory = $processDirectory . DIRECTORY_SEPARATOR . trim(trim($this->data['subDir'], '/'), '\\');
        }

        return $processDirectory .
            DIRECTORY_SEPARATOR . substr(md5(time()), 0, self::DIR_LENGTH_FIRST) .
            DIRECTORY_SEPARATOR . substr(md5(microtime() . $this->file->getBasename()), 0, self::DIR_LENGTH_SECOND);
    }

    /**
     * @param ThumbConfig $thumbConfig
     * @return string
     */
    protected function createThumb(ThumbConfig $thumbConfig)
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

        $thumbContent = ImageHelper::thumbnail(
            ImageHelper::getImagine()->load(Storage::disk($this->currentDisk)->get($this->mediafileModel->url)),
            $thumbConfig->getWidth(),
            $thumbConfig->getHeight(),
            $thumbConfig->getMode()
        )->get($originalPathInfo['extension']);

        Storage::disk($this->currentDisk)->put($thumbPath, $thumbContent);

        return $thumbPath;
    }

    /**
     * @param $original
     * @param $extension
     * @param $alias
     * @param $width
     * @param $height
     * @return string
     */
    protected function getThumbFilename($original, $extension, $alias, $width, $height)
    {
        return strtr($this->thumbFilenameTemplate, [
            '{original}' => $original,
            '{extension}' => $extension,
            '{alias}' => $alias,
            '{width}' => $width,
            '{height}' => $height,
        ]);
    }

    protected function setBaseMediafileData(): void
    {
        $this->mediafileModel->url = $this->databaseUrl;
        $this->mediafileModel->filename = $this->outFileName;
        $this->mediafileModel->size = $this->file->getSize();
        $this->mediafileModel->type = $this->file->getMimeType();
        $this->mediafileModel->disk = $this->currentDisk;
    }

    protected function setTextMediafileData(): void
    {
        $this->mediafileModel->alt = $this->data['alt'];
        $this->mediafileModel->title = $this->data['title'];
        $this->mediafileModel->description = $this->data['description'];
    }
}
