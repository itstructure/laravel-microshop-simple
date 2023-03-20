<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;

class Mediafile extends Model
{
    protected $table = 'mediafiles';

    protected $fillable = ['filename', 'type', 'url', 'alt', 'size', 'title', 'description', 'thumbs', 'disk'];

    protected $casts = [
        'thumbs' => 'array',
    ];

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function addOwner(int $ownerId, string $ownerName, string $ownerAttribute): bool
    {
        return OwnerMediafile::addOwner($this->id, $ownerId, $ownerName, $ownerAttribute);
    }
}
