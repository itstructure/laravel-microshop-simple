<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\{Model, Builder as EloquentBuilder};

abstract class Owner extends Model
{
    /**
     * Get model (mediafile/album) primary key name.
     * @return string
     */
    abstract protected static function getExternalModelKeyName(): string;

    /**
     * Add owner to mediafiles table.
     * @param int    $modelId
     * @param int    $ownerId
     * @param string $owner
     * @param string $ownerAttribute
     * @return bool
     */
    public static function addOwner(int $modelId, int $ownerId, string $owner, string $ownerAttribute): bool
    {
        $ownerModel = new static();
        $ownerModel->{static::getExternalModelKeyName()} = $modelId;
        $ownerModel->owner_id = $ownerId;
        $ownerModel->owner = $owner;
        $ownerModel->owner_attribute = $ownerAttribute;

        return $ownerModel->save();
    }

    /**
     * Remove this mediafile/album owner.
     * @param int $ownerId
     * @param string $owner
     * @param string|null $ownerAttribute
     * @return bool
     */
    public static function removeOwner(int $ownerId, string $owner, string $ownerAttribute = null): bool
    {
        $query = static::query();
        foreach (static::buildFilterOptions($ownerId, $owner, $ownerAttribute) as $attribute => $value) {
            /* @var QueryBuilder $q */
            $query->where($attribute, $value);
        }

        return $query->delete() > 0;
    }

    /**
     * Getting entity id's which are related with Other owners too.
     * @param string $owner
     * @param int $ownerId
     * @param array $entityIds
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function filterMultipliedEntityIds(string $owner, int $ownerId, array $entityIds)
    {
        return static::query()->select(static::getExternalModelKeyName())
            ->where([static::getExternalModelKeyName() => $entityIds])
            ->where(function ($q) use ($ownerId, $owner) {
                /* @var QueryBuilder $q */
                $q->where('owner_id', '!=', $ownerId)
                    ->orWhere('owner', '!=', $owner);
            })
            ->get();
    }

    /**
     * Get Id's by owner.
     * @param string $nameId
     * @param array $args It can be an array of the next params: owner{string}, ownerId{int}, ownerAttribute{string}.
     * @return EloquentBuilder
     */
    protected static function getEntityIdsQuery(string $nameId, array $args): EloquentBuilder
    {
        return static::query()->select($nameId)->when(count($args) > 0, function($q) use ($args) {
            foreach ($args as $attribute => $value) {
                /* @var QueryBuilder $q */
                $q->where($attribute, $value);
            }
        });
    }

    /**
     * Build filter options for some actions.
     * @param int $ownerId
     * @param string $owner
     * @param string|null $ownerAttribute
     * @return array
     */
    protected static function buildFilterOptions(int $ownerId, string $owner, string $ownerAttribute = null): array
    {
        return array_merge([
            'owner_id' => $ownerId,
            'owner' => $owner
        ], empty($ownerAttribute) ? [] : ['owner_attribute' => $ownerAttribute]);
    }
}
