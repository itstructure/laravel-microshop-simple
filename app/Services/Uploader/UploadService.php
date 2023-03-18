<?php

namespace App\Services\Uploader;

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

class UploadService
{
    public static function getInstance(array $config): self
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }


    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        $this->detectScenario();

        if (!$this->validate()) {
            return false;
        }

        if (null !== $this->file) {

            if ($this->scenario == self::SCENARIO_UPLOAD) {
                $this->setParamsForUpload();

            } else if ($this->scenario == self::SCENARIO_UPDATE) {
                $this->setParamsForUpdate();
            }

            if (!$this->sendFile()) {
                throw new \Exception('Error upload file.');
            }

            if ($this->scenario == self::SCENARIO_UPDATE) {
                $this->deletePreviousFiles();
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
        $this->setDirectoryForDelete();

        $this->deleteDirectoryWithFiles();

        $deleted = $this->mediafileModel->delete();

        if (empty($deleted)) {
            throw new \Exception('Error delete file data from database.', 500);
        }

        return $deleted;
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
            $thumbs[$alias] = $this->createThumb(ThumbHelper::configureThumb($alias, $preset));
        }

        // Create default thumb.
        if (!array_key_exists(self::THUMB_ALIAS_DEFAULT, $this->thumbSizes)) {
            $defaultThumbConfig = ThumbHelper::configureThumb(self::THUMB_ALIAS_DEFAULT, ThumbHelper::getDefaultSizes());
            $thumbs[self::THUMB_ALIAS_DEFAULT] = $this->createThumb($defaultThumbConfig);
        }

        $this->mediafileModel->thumbs = serialize($thumbs);

        return $this->mediafileModel->save();
    }


    /********************** PROCESS INTERNAL METHODS *********************/
    private function detectScenario(): void
    {
        if (empty($this->mediafileModel->getKey())) {
            $this->scenario = self::SCENARIO_UPLOAD;
        } else {
            $this->scenario = self::SCENARIO_UPDATE;
        }
    }

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
     * @return bool
     */
    private function sendFile(): bool
    {
        Storage::disk($this->currentDisk)->putFileAs($this->uploadDirectory, $this->file, $this->outFileName);

        return Storage::disk($this->currentDisk)->fileExists($this->uploadDirectory . DIRECTORY_SEPARATOR . $this->outFileName);
    }

    /**
     * @return bool
     */
    private function deleteDirectoryWithFiles(): bool
    {
        return Storage::disk($this->currentDisk)->deleteDirectory($this->directoryForDelete);
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
    private function setParamsForUpload(): void
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

    private function setParamsForUpdate(): void
    {
        $originalPathinfo = pathinfo($this->mediafileModel->getUrl());
        $this->uploadDirectory = $originalPathinfo['dirname'];
    }

    /**
     * @return void
     */
    private function setDirectoryForDelete(): void
    {
        $originalPathinfo = pathinfo($this->mediafileModel->getUrl());

        $dirnameParent = substr($originalPathinfo['dirname'], 0, -(self::DIR_LENGTH_SECOND + 1));
        $childDirectories = Storage::disk($this->currentDisk)->directories($dirnameParent);

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
            '{original}' => $original,
            '{extension}' => $extension,
            '{alias}' => $alias,
            '{width}' => $width,
            '{height}' => $height,
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
