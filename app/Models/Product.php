<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{Titleable, Aliasable};

class Product extends Model
{
    use Titleable, Aliasable;

    protected $table = 'products';

    protected $fillable = ['title', 'alias', 'description', 'price', 'category_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
