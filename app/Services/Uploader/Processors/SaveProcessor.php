<?php

namespace App\Services\Uploader\Processors;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\{
    MessageBag, Str
};
use Illuminate\Support\Facades\{
    Storage, Validator
};
use App\Services\Uploader\Classes\ThumbConfig;
use App\Services\Uploader\Helpers\{
    ImageHelper, ThumbHelper
};
use App\Services\Uploader\Models\Mediafile;

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

    /**
     * @var MessageBag
     */
    protected $errors;


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


    /********************** PROCESS INTERNAL METHODS *********************/
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
}
