<?php

namespace App\Services\Uploader\Processors;

use \Exception;
use Illuminate\Http\UploadedFile;
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

    /**
     * Alt text for the file.
     * @var string
     */
    public $alt;

    /**
     * Title for the file.
     * @var string
     */
    public $title;

    /**
     * File description.
     * @var string
     */
    public $description;

    /**
     * Addition sub-directory for uploaded files.
     * @var string
     */
    public $subDir;

    /**
     * Owner name (post, page, article e.t.c.).
     * @var string
     */
    public $owner;

    /**
     * Owner id.
     * @var int
     */
    public $ownerId;

    /**
     * Owner attribute (image, audio, thumbnail e.t.c.).
     * @var string
     */
    public $ownerAttribute;

    /**
     * Needed file type for validation (thumbnail, image e.t.c.).
     * @var string
     */
    public $neededFileType;

    /**
     * Rename file after upload.
     * @var bool
     */
    public $renameFiles = true;

    /**
     * File extensions.
     * @var array
     */
    public $fileExtensions = [
        self::FILE_TYPE_THUMB => [
            'png', 'jpg', 'jpeg', 'gif',
        ],
        self::FILE_TYPE_IMAGE => [
            'png', 'jpg', 'jpeg', 'gif',
        ],
        self::FILE_TYPE_AUDIO => [
            'mp3',
        ],
        self::FILE_TYPE_VIDEO => [
            'mp4', 'ogg', 'ogv', 'oga', 'ogx', 'webm',
        ],
        self::FILE_TYPE_APP => [
            'doc', 'docx', 'rtf', 'pdf', 'rar', 'zip', 'jar', 'mcd', 'xls',
        ],
        self::FILE_TYPE_TEXT => [
            'txt',
        ],
        self::FILE_TYPE_OTHER => null,
    ];

    /**
     * Check extension by MIME type (they are must match).
     * @var bool
     */
    public $checkExtensionByMimeType = true;

    /**
     * Maximum file size.
     * @var int
     */
    public $fileMaxSize = 1024*1024*64;

    /**
     * Thumbs config with their types and sizes.
     * @var array
     */
    public $thumbsConfig = [];

    /**
     * Thumbnails name template.
     * Values can be the next: {original}, {width}, {height}, {alias}, {extension}
     * @var string
     */
    public $thumbFilenameTemplate = '{original}-{width}-{height}-{alias}.{extension}';

    /**
     * Directories for uploaded files depending on the file type.
     * @var array
     */
    public $uploadDirs;

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
                throw new \Exception('Error upload file.', 500);
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

        $this->mediafileModel->alt = $this->alt;
        $this->mediafileModel->title = $this->title;
        $this->mediafileModel->description = $this->description;

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.', 500);
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
     * Set attributes with their values.
     * @param $values
     * @return mixed
     */
    public function setAttributes($values)
    {

    }

    /**
     * Validate data.
     * @return mixed
     */
    public function validate()
    {
        $validator = Validator::make([], $this->validateRules($this->scenario));
    }

    /**
     * Returns the errors for all attributes.
     * @return array.
     */
    public function getErrors()
    {

    }

    protected function validateRules(string $scenario): array
    {
        return [

        ];
    }
}