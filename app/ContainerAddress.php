<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ContainerAddress
 *
 * @property int $id
 * @property string $address 装箱地点
 * @property int $is_up 是否装箱地点
 * @property int $is_down 是否送箱地点
 * @property int $status
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ContainerAddressData[] $container_address_data
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereIsDown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereIsUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContainerAddress extends Model
{
    function container_address_data()
    {
        return $this->hasMany(ContainerAddressData::class, 'container_address_id', 'id');
    }

    function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
