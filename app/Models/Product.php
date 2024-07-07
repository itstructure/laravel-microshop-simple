<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Itstructure\MFU\Interfaces\BeingOwnerInterface;
use Itstructure\MFU\Behaviors\Owner\{BehaviorMediafile, BehaviorAlbum};
use App\Traits\{Titleable, Aliasable, Thumbnailable};

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property float $price
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model implements BeingOwnerInterface
{
    use Titleable, Aliasable, Thumbnailable;

    /**
     * @var string|int
     */
    public $thumbnail;

    /**
     * @var string[]|int[]
     */
    public $image;

    /**
     * @var string[]|int[]
     */
    public $image_album;

    /**
     * @var bool
     */
    protected $removeDependencies = false;

    protected $table = 'products';

    protected $fillable = ['title', 'alias', 'description', 'price', 'category_id'];

    /**
     * @return string
     */
    public function getItsName(): string
    {
        return $this->getTable();
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->getKey();
    }

    /**
     * @param bool $removeDependencies
     * @return $this
     */
    public function setRemoveDependencies(bool $removeDependencies)
    {
        $this->removeDependencies = $removeDependencies;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRemoveDependencies(): bool
    {
        return $this->removeDependencies;
    }

    /**
     * @return array
     */
    public static function getBehaviorMadiafileAttributes(): array
    {
        return ['thumbnail', 'image'];
    }

    /**
     * @return array
     */
    public static function getBehaviorAlbumAttributes(): array
    {
        return ['image_album'];
    }

    /**
     * @return array
     */
    public static function getAllBehaviorAttributes(): array
    {
        return array_merge(static::getBehaviorMadiafileAttributes(), static::getBehaviorAlbumAttributes());
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function fill(array $attributes)
    {
        foreach (static::getAllBehaviorAttributes() as $behaviorAttribute) {
            if (isset($attributes[$behaviorAttribute])) {
                $this->{$behaviorAttribute} = $attributes[$behaviorAttribute];
            }
        }
        return parent::fill($attributes);
    }

    protected static function booted(): void
    {
        $behaviorMediafile = BehaviorMediafile::getInstance(static::getBehaviorMadiafileAttributes());
        $behaviorAlbum = BehaviorAlbum::getInstance(static::getBehaviorAlbumAttributes());

        static::saved(function (Model $ownerModel) use ($behaviorMediafile, $behaviorAlbum) {
            if ($ownerModel->wasRecentlyCreated) {
                $behaviorMediafile->link($ownerModel);
                $behaviorAlbum->link($ownerModel);
            } else {
                $behaviorMediafile->refresh($ownerModel);
                $behaviorAlbum->refresh($ownerModel);
            }
        });

        static::deleted(function (Model $ownerModel) use ($behaviorMediafile, $behaviorAlbum) {
            $behaviorMediafile->clear($ownerModel);
            $behaviorAlbum->clear($ownerModel);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
