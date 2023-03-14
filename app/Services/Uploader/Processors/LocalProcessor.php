<?php

namespace App\Services\Uploader\Processors;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use App\Services\Uploader\UploaderService;

class LocalProcessor extends BaseProcessor
{
    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var string
     */
    private $uploadRoot;

    /************************* PROCESS ATTRIBUTES ************************/
    /**
     * @var string
     */
    private $directoryForDelete;

    /************************* CONFIG SETTERS ****************************/
    /**
     * @param string $uploadRoot
     * @return LocalProcessor
     */
    public function setUploadRoot(string $uploadRoot): self
    {
        $this->uploadRoot = rtrim(rtrim($uploadRoot, '/'), '\\');
        return $this;
    }


    /************************* PROCESS METHODS ***************************/
    /**
     * Get storage type - local.
     * @return string
     */
    protected function getStorageType(): string
    {
        return UploaderService::STORAGE_TYPE_LOCAL;
    }

    /**
     * Set some params for send.
     * It is needed to set the next parameters:
     * $this->uploadDir
     * $this->uploadPath
     * $this->outFileName
     * $this->databaseUrl
     * @throws \Exception
     * @return void
     */
    protected function setParamsForSend(): void
    {
        $uploadDir = rtrim(rtrim($this->getUploadDirConfig($this->file->getMimeType()), '/'), '\\');

        if (!empty($this->data['subDir'])) {
            $uploadDir = $uploadDir . DIRECTORY_SEPARATOR . trim(trim($this->data['subDir'], '/'), '\\');
        }

        $this->uploadDir = $uploadDir .
            DIRECTORY_SEPARATOR . substr(md5(time()), 0, self::DIR_LENGTH_FIRST) .
            DIRECTORY_SEPARATOR . substr(md5(microtime() . $this->file->getBasename()), 0, self::DIR_LENGTH_SECOND);

        $this->uploadPath = $this->uploadRoot . DIRECTORY_SEPARATOR . ltrim(ltrim($this->uploadDir, '/'), '\\');

        $this->outFileName = $this->renameFiles ?
            Str::uuid() . '.' . $this->file->getExtension() :
            Str::slug($this->file->getBasename()) . '.' . $this->file->getExtension();

        $this->databaseUrl = $this->uploadDir . DIRECTORY_SEPARATOR . $this->outFileName;
    }

    /**
     * Set some params for delete.
     * It is needed to set the next parameters:
     * $this->directoryForDelete
     * @return void
     */
    protected function setParamsForDelete(): void
    {
        $originalFile = pathinfo($this->uploadRoot . DIRECTORY_SEPARATOR . ltrim(ltrim($this->mediafileModel->getUrl(), '\\'), '/'));

        $dirnameParent = substr($originalFile['dirname'], 0, -(self::DIR_LENGTH_SECOND + 1));

        $childDirectories = Finder::create()->in($dirnameParent)->directories()->depth(0)->sortByName();
        $this->directoryForDelete = count($childDirectories) == 1 ? $dirnameParent : $originalFile['dirname'];
    }

    /**
     * Save file in local directory.
     *
     * @return bool
     */
    protected function sendFile(): bool
    {
        BaseFileHelper::createDirectory($this->uploadPath, 0777);

        $savePath = $this->uploadPath . DIRECTORY_SEPARATOR . $this->outFileName;

        $this->file->saveAs($savePath);

        return file_exists($savePath);
    }

    /**
     * Delete local directory with original file and thumbs.
     *
     * @return void
     */
    protected function deleteFiles(): void
    {
        BaseFileHelper::removeDirectory($this->directoryForDelete);
    }

    /**
     * Create thumb.
     *
     * @param ThumbConfigInterface|ThumbConfig $thumbConfig
     *
     * @return string
     */
    protected function createThumb(ThumbConfigInterface $thumbConfig)
    {
        $originalFile = pathinfo($this->mediafileModel->url);

        $thumbUrl = $originalFile['dirname'] .
            DIRECTORY_SEPARATOR .
            $this->getThumbFilename($originalFile['filename'],
                $originalFile['extension'],
                $thumbConfig->getAlias(),
                $thumbConfig->getWidth(),
                $thumbConfig->getHeight()
            );

        Image::thumbnail($this->uploadRoot . DIRECTORY_SEPARATOR . ltrim(ltrim($this->mediafileModel->url, '\\'), '/'),
            $thumbConfig->getWidth(),
            $thumbConfig->getHeight(),
            $thumbConfig->getMode()
        )->save($this->uploadRoot . DIRECTORY_SEPARATOR . ltrim(ltrim($thumbUrl, '\\'), '/'));

        return $thumbUrl;
    }

    /**
     * Actions after main save.
     *
     * @return mixed
     */
    protected function afterSave()
    {
        if (null !== $this->owner && null !== $this->ownerId && null != $this->ownerAttribute) {
            $this->mediafileModel->addOwner($this->ownerId, $this->owner, $this->ownerAttribute);
        }
    }
}