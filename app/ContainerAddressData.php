<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "container_address_data".
 *
 * @property string $id
 * @property int $container_address_id
 * @property int $segment_business_id
 * @property int $master_business_id
 * @property int $slaver_business_id
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Business $master_businesses
 * @property-read \App\Business $segment_businesses
 * @property-read \App\Business $slaver_businesses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereContainerAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerAddressData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\User $users
 */
class ContainerAddressData extends Model
{
    function segment_businesses(){
        return $this->hasOne('\App\Business','id','segment_business_id');
    }
    function master_businesses(){
        return $this->hasOne('\App\Business','id','master_business_id');
    }
    function slaver_businesses(){
        return $this->hasOne('\App\Business','id','slaver_business_id');
    }
    function users(){
    	return $this->belongsTo(User::class,"user_id","id");
    }
}
