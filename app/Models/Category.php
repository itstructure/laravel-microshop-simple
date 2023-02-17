<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{Titleable, Aliasable};

class Category extends Model
{
    use Titleable, Aliasable;

    protected $table = 'categories';

    protected $fillable = ['parent_id', 'title', 'alias'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
