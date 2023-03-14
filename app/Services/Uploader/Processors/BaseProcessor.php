<?php

namespace App\Services\Uploader\Processors;

use \Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use App\Services\Uploader\Models\Mediafile;
use App\Services\Uploader\Interfaces\ThumbConfigInterface;

abstract class BaseProcessor
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
    protected $thumbsConfig;

    /**
     * @var string
     */
    protected $thumbFilenameTemplate;

    /**
     * @var array
     */
    protected $uploadDirs;


    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Directory for uploaded files.
     * @var string
     */
    protected $uploadDir;

    /**
     * Full directory path to upload file.
     * @var string
     */
    protected $uploadPath;

    /**
     * Prepared file name to save in database and storage.
     * @var string
     */
    protected $outFileName;

    /**
     * File url path for database.
     * @var string
     */
    protected $databaseUrl;

    /**
     * @var string
     */
    protected $scenario;

    /**
     * @var Mediafile
     */
    protected $mediafileModel;

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * Set params for send file.
     * @return void
     */
    abstract protected function setParamsForSend(): void;

    /**
     * Set some params for delete.
     * @return void
     */
    abstract protected function setParamsForDelete(): void;

    /**
     * Send file to local directory or send file to remote storage.
     * @return bool
     */
    abstract protected function sendFile(): bool;

    /**
     * Delete files from local directory or from remote storage.
     * @return void
     */
    abstract protected function deleteFiles(): void;

    /**
     * Create thumb.
     * @param ThumbConfigInterface $thumbConfig
     * @return string|null
     */
    abstract protected function createThumb(ThumbConfigInterface $thumbConfig);

    /**
     * Get storage type (local, s3, e.t.c...).
     * @return string
     */
    abstract protected function getStorageType(): string;

    /**
     * Actions after main save.
     * @return mixed
     */
    abstract protected function afterSave();

    /**
     * @param array $config
     * @return static
     */
    public static function getInstance(array $config)
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }


    /************************* CONFIG SETTERS ****************************/
    /**
     * @param bool $renameFiles
     * @return BaseProcessor
     */
    public function setRenameFiles(bool $renameFiles): self
    {
        $this->renameFiles = $renameFiles;
        return $this;
    }

    /**
     * @param bool $checkExtensionByMimeType
     * @return BaseProcessor
     */
    public function setCheckExtensionByMimeType(bool $checkExtensionByMimeType): self
    {
        $this->checkExtensionByMimeType = $checkExtensionByMimeType;
        return $this;
    }

    /**
     * @param int $fileMaxSize
     * @return BaseProcessor
     */
    public function setFileMaxSize(int $fileMaxSize): self
    {
        $this->fileMaxSize = $fileMaxSize;
        return $this;
    }

    /**
     * @param array $fileExtensions
     * @return BaseProcessor
     */
    public function setFileExtensions(array $fileExtensions): self
    {
        $this->fileExtensions = $fileExtensions;
        return $this;
    }

    /**
     * @param array $thumbsConfig
     * @return BaseProcessor
     */
    public function setThumbsConfig(array $thumbsConfig): self
    {
        $this->thumbsConfig = $thumbsConfig;
        return $this;
    }

    /**
     * @param string $thumbFilenameTemplate
     * @return BaseProcessor
     */
    public function setThumbFilenameTemplate(string $thumbFilenameTemplate): self
    {
        $this->thumbFilenameTemplate = $thumbFilenameTemplate;
        return $this;
    }

    /**
     * @param array $uploadDirs
     * @return BaseProcessor
     */
    public function setUploadDirs(array $uploadDirs): self
    {
        $this->uploadDirs = $uploadDirs;
        return $this;
    }


    /************************* PROCESS METHODS ***************************/
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
     * Set mediafile model.
     * @param Mediafile $model
     * @return $this
     */
    public function setMediafileModel(Mediafile $model)
    {
        $this->mediafileModel = $model;
        return $this;
    }

    /**
     * Get mediafile model.
     * @return Mediafile
     */
    public function getMediafileModel(): Mediafile
    {
        return $this->mediafileModel;
    }

    /**
     * Set file.
     * @param UploadedFile|null $file
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file.
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * Save file in storage and database.
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
            $this->mediafileModel->storage = $this->getStorageType();
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
     * Delete file from storage and database.
     * @return int
     */
    public function delete(): int
    {

    }

    /**
     * Returns current model id.
     * @return int|string
     */
    public function getId()
    {

    }

    /**
     * Create thumbs for this image
     * @throws Exception
     * @return bool
     */
    public function createThumbs(): bool
    {

    }

    /**
     * Validate data.
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
     * @param string $scenario
     * @return array
     */
    protected function validateRules(string $scenario): array
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
     * Get upload directory configuration by file type.
     * @param string $fileType
     * @throws Exception
     * @return string
     */
    protected function getUploadDirConfig(string $fileType): string
    {
        if (!is_array($this->uploadDirs) || empty($this->uploadDirs)) {
            throw new Exception('The localUploadDirs is not defined.');
        }

        if (strpos($fileType, self::FILE_TYPE_IMAGE) !== false) {
            return $this->uploadDirs[self::FILE_TYPE_IMAGE];

        } elseif (strpos($fileType, self::FILE_TYPE_AUDIO) !== false) {
            return $this->uploadDirs[self::FILE_TYPE_AUDIO];

        } elseif (strpos($fileType, self::FILE_TYPE_VIDEO) !== false) {
            return $this->uploadDirs[self::FILE_TYPE_VIDEO];

        } elseif (strpos($fileType, self::FILE_TYPE_APP) !== false) {
            return $this->uploadDirs[self::FILE_TYPE_APP];

        } elseif (strpos($fileType, self::FILE_TYPE_TEXT) !== false) {
            return $this->uploadDirs[self::FILE_TYPE_TEXT];

        } else {
            return $this->uploadDirs[self::FILE_TYPE_OTHER];
        }
    }
}