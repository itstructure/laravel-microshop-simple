<?php

namespace App\Services\Uploader\Processors;

class UploadProcessor extends SaveProcessor
{
    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $uploadDirectories;


    /************************* CONFIG SETTERS ****************************/
    /**
     * @param array $uploadDirectories
     * @return $this
     */
    public function setUploadDirectories(array $uploadDirectories)
    {
        $this->uploadDirectories = $uploadDirectories;
        return $this;
    }
}
