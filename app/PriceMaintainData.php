<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PriceMaintainData
 *
 * @property-read \App\Business $master_businesses
 * @property-read \App\Business $segment_businesses
 * @property-read \App\Business $slaver_businesses
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintainData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintainData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintainData query()
 * @mixin \Eloquent
 */
class PriceMaintainData extends Model
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
