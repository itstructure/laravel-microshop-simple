<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\{Collection, Builder as EloquentBuilder};
use App\Services\Uploader\Traits\HasCompositePrimaryKey;

class OwnerAlbum extends Owner
{
    use HasCompositePrimaryKey;

    public $incrementing = false;

    protected $primaryKey = ['album_id', 'owner_id', 'owner_name', 'owner_attribute'];

    protected $table = 'owners_albums';

    protected $fillable = ['album_id', 'owner_id', 'owner_name', 'owner_attribute'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getAlbum()
    {
        return $this->hasOne(Album::class, ['id' => 'album_id']);
    }

    /**
     * Get all albums by owner.
     * @param string $ownerName
     * @param int $ownerId
     * @param string|null $ownerAttribute
     * @return Collection|Album[]
     */
    public static function getAlbums(string $ownerName, int $ownerId, string $ownerAttribute = null)
    {
        return static::getAlbumsQuery(static::buildFilterOptions($ownerId, $ownerName, $ownerAttribute))->get();
    }

    /**
     * Get all albums query by owner.
     * @param array $args. It can be an array of the next params: owner{string}, ownerId{int}, ownerAttribute{string}.
     * @return EloquentBuilder
     */
    public static function getAlbumsQuery(array $args = [])
    {
        return Album::query()->whereIn('id', static::getEntityIdsQuery('album_id', $args)->get()->pluck('album_id'));
    }

    /**
     * Get image albums by owner.
     * @param string $ownerName
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getImageAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_IMAGE);
    }

    /**
     * Get audio albums by owner.
     * @param string $ownerName
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getAudioAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_AUDIO);
    }

    /**
     * Get video albums by owner.
     * @param string $ownerName
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getVideoAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_VIDEO);
    }

    /**
     * Get application albums by owner.
     * @param string $ownerName
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getAppAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_APP);
    }

    /**
     * Get text albums by owner.
     * @param string $owner
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getTextAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_TEXT);
    }

    /**
     * Get other albums by owner.
     * @param string $ownerName
     * @param int    $ownerId
     * @return Collection|Album[]
     */
    public static function getOtherAlbums(string $ownerName, int $ownerId)
    {
        return static::getAlbums($ownerName, $ownerId, Album::ALBUM_TYPE_OTHER);
    }

    /**
     * Get model album primary key name.
     * @return string
     */
    protected static function getExternalModelKeyName(): string
    {
        return 'album_id';
    }
}
