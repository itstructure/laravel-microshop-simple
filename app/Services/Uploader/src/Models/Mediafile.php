<?php

namespace App\Services\Uploader\src\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Uploader\src\Processors\SaveProcessor;

class Mediafile extends Model
{
    protected $table = 'mediafiles';

    protected $fillable = ['file_name', 'mime_type', 'path', 'alt', 'size', 'title', 'description', 'thumbs', 'disk'];

    protected $casts = [
        'thumbs' => 'array',
    ];

    /**
     * @param array $mimeTypes
     * @return \Illuminate\Support\Collection
     */
    public static function findByMimeTypes(array $mimeTypes)
    {
        return static::whereIn('mime_type', $mimeTypes)->get();
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @return array
     */
    public function getThumbs(): array
    {
        return $this->thumbs;
    }

    /**
     * @param string $alias
     * @return array
     */
    public function getThumbPath(string $alias): array
    {
        if ($alias === SaveProcessor::THUMB_ALIAS_ORIGINAL) {
            return $this->getPath();

        } else {
            $thumbs = $this->getThumbs();
            $path = !empty($thumbs[$alias]) ? $thumbs[$alias] : '';
        }

        if (empty($path)) {
            return '';
        }

        return $this->getPath();
    }

    /**
     * @param int $ownerId
     * @param string $ownerName
     * @param string $ownerAttribute
     * @return bool
     */
    public function addOwner(int $ownerId, string $ownerName, string $ownerAttribute): bool
    {
        return OwnerMediafile::addOwner($this->id, $ownerId, $ownerName, $ownerAttribute);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getOwners()
    {
        return $this->hasMany(OwnerMediafile::class, 'mediafile_id', 'id');
    }

    /**
     * Check if the file is image.
     * @return bool
     */
    public function isImage(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_IMAGE) !== false;
    }

    /**
     * Check if the file is audio.
     * @return bool
     */
    public function isAudio(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_AUDIO) !== false;
    }

    /**
     * Check if the file is video.
     * @return bool
     */
    public function isVideo(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_VIDEO) !== false;
    }

    /**
     * Check if the file is text.
     * @return bool
     */
    public function isText(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_TEXT) !== false;
    }

    /**
     * Check if the file is application.
     * @return bool
     */
    public function isApp(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_APP) !== false;
    }

    /**
     * Check if the file is excel.
     * @return bool
     */
    public function isExcel(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_APP_EXCEL) !== false;
    }

    /**
     * Check if the file is pdf.
     * @return bool
     */
    public function isPdf(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_APP_PDF) !== false;
    }

    /**
     * Check if the file is word.
     * @return bool
     */
    public function isWord(): bool
    {
        return strpos($this->mime_type, SaveProcessor::FILE_TYPE_APP_WORD) !== false;
    }
}
