<?php

namespace App\Services\Uploader\Processors;

use Illuminate\Http\UploadedFile;

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


    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var UploadedFile
     */
    protected $file;


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
}
