<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Route
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Route onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Route withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Route withoutTrashed()
 * @mixin \Eloquent
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $master_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $segment_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $slaver_businesses
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereUserId($value)
 */
class Route extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'status' => 1,
    ];

    function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    function segment_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_route', 'route_id', 'segment_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function master_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_route', 'route_id', 'master_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function slaver_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_route', 'route_id', 'slaver_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }
}
