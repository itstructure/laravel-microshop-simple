<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Itstructure\MFU\Models\Owners\OwnerMediafile;
use Itstructure\MFU\Models\Mediafile;

/**
 * Class Thumbnailable
 * @method Model getTable()
 * @method Model getKey()
 * @package App\Traits
 */
trait Thumbnailable
{
    public function getThumbnailModel(): ?Mediafile
    {
        return OwnerMediafile::getOwnerThumbnailModel($this->getTable(), $this->getKey());
    }
}