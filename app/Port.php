<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Port
 *
 * @property int $id
 * @property string $name 港口名称
 * @property string $name_code 助记码
 * @property string $country 国家
 * @property int $user_id 操作人
 * @property int $status 0:禁用,1:启用
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $businesses
 * @property-read \App\User $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Port onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereNameCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Port whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Port withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Port withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $master_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $segment_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $slaver_businesses
 */
class Port extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'status' => 1,
    ];

    function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
//        return $this->belongsTo(User::class);
//        return $this->hasOne(User::class);
    }

    function segment_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_port', 'port_id', 'segment_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function master_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_port', 'port_id', 'master_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function slaver_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_port', 'port_id', 'slaver_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }
}
