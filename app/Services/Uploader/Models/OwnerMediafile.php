<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\{Collection, Builder as EloquentBuilder};
use App\Services\Uploader\Processors\BaseProcessor;
use App\Services\Uploader\Traits\HasCompositePrimaryKey;

class OwnerMediafile extends Owner
{
    use HasCompositePrimaryKey;

    public $incrementing = false;

    protected $primaryKey = ['mediafile_id', 'owner_id', 'owner', 'owner_attribute'];

    protected $table = 'owners_mediafiles';

    protected $fillable = ['mediafile_id', 'owner_id', 'owner', 'owner_attribute'];

    public function getMediaFile()
    {
        return $this->hasOne(Mediafile::class, ['id' => 'mediafileId']);
    }

    /**
     * Get all mediafiles by owner.
     * @param string $owner
     * @param int    $ownerId
     * @param null|string $ownerAttribute
     * @return Collection|Mediafile[]
     */
    public static function getMediaFiles(string $owner, int $ownerId, string $ownerAttribute = null)
    {
        return static::getMediaFilesQuery(static::buildFilterOptions($ownerId, $owner, $ownerAttribute))->get();
    }

    /**
     * Get all mediafiles query by owner.
     * @param array $args. It can be an array of the next params: owner{string}, ownerId{int}, ownerAttribute{string}.
     * @return EloquentBuilder
     */
    public static function getMediaFilesQuery(array $args = [])
    {
        return Mediafile::query()->whereIn('id', static::getEntityIdsQuery('mediafile_id', $args)->get()->pluck('mediafile_id'));
    }

    /**
     * Get one owner thumbnail file by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Mediafile|null
     */
    public static function getOwnerThumbnail(string $owner, int $ownerId)
    {
        $ownerMediafileModel = static::getEntityIdsQuery('mediafile_id', [
            'owner' => $owner,
            'owner_id' => $ownerId,
            'owner_attribute' => BaseProcessor::FILE_TYPE_THUMB,
        ])->first();

        if (null === $ownerMediafileModel) {
            return null;
        }

        return Mediafile::find($ownerMediafileModel->mediafile_id);
    }

    /**
     * Get image files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getImageFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_IMAGE);
    }

    /**
     * Get audio files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getAudioFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_AUDIO);
    }

    /**
     * Get video files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getVideoFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_VIDEO);
    }

    /**
     * Get app files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getAppFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_APP);
    }

    /**
     * Get text files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getTextFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_TEXT);
    }

    /**
     * Get other files by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Mediafile[]
     */
    public static function getOtherFiles(string $owner, int $ownerId)
    {
        return static::getMediaFiles($owner, $ownerId, BaseProcessor::FILE_TYPE_OTHER);
    }

    /**
     * Get model mediafile primary key name.
     * @return string
     */
    protected static function getExternalModelKeyName(): string
    {
        return 'mediafile_id';
    }
}
