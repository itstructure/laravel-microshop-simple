<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 * @property string $alias
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $user_name
 * @property string $user_email
 * @property string|null $user_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserName($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
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
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $member_key
 * @property-read string $member_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property \Illuminate\Database\Eloquent\Collection<int, \Itstructure\LaRbac\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 */
	class User extends \Eloquent implements \Itstructure\LaRbac\Interfaces\RbacUserInterface {}
}

namespace App\Services\Uploader\Models{
/**
 * App\Services\Uploader\Models\Album
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 */
	class Album extends \Eloquent {}
}

namespace App\Services\Uploader\Models{
/**
 * App\Services\Uploader\Models\Mediafile
 *
 * @property int $id
 * @property string $filename
 * @property string $type
 * @property string $url
 * @property string|null $alt
 * @property int $size
 * @property string|null $title
 * @property string|null $description
 * @property array $thumbs
 * @property string $disk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereThumbs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mediafile whereUrl($value)
 */
	class Mediafile extends \Eloquent {}
}

namespace App\Services\Uploader\Models{
/**
 * App\Services\Uploader\Models\OwnerAlbum
 *
 * @property int $album_id
 * @property int $owner_id
 * @property string $owner_name
 * @property string $owner_attribute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum query()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereOwnerAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerAlbum whereUpdatedAt($value)
 */
	class OwnerAlbum extends \Eloquent {}
}

namespace App\Services\Uploader\Models{
/**
 * App\Services\Uploader\Models\OwnerMediafile
 *
 * @property int $mediafile_id
 * @property int $owner_id
 * @property string $owner_name
 * @property string $owner_attribute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile query()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereMediafileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereOwnerAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerMediafile whereUpdatedAt($value)
 */
	class OwnerMediafile extends \Eloquent {}
}

