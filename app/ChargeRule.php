<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ChargeRule
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule query()
 * @mixin \Eloquent
 * @property-read \App\User $create_users
 * @property-read \App\CustomerSupplier $customer_suppliers
 * @property-read \App\Business $master_businesses
 * @property-read \App\Business $segment_businesses
 * @property-read \App\Business $slaver_businesses
 * @property-read \App\User $update_users
 * @property-read \App\User $users
 * @property-read \App\User $verify_users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRule onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRule withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRule withoutTrashed()
 */
class ChargeRule extends Model
{
    use SoftDeletes;

    function segment_businesses(){
        return $this->belongsTo(Business::class,'segment_business_id','id');
    }

    function master_businesses(){
        return $this->belongsTo(Business::class,'master_business_id','id');
    }

    function slaver_businesses(){
        return $this->belongsTo(Business::class,'slaver_business_id','id');
    }

    function create_users(){
        return $this->belongsTo(User::class, "create_user_id", "id");
    }

    function update_users(){
        return $this->belongsTo(User::class, "update_user_id", "id");
    }

    function verify_users(){
        return $this->belongsTo(User::class, "verify_user_id", "id");
    }

    function users(){
        return $this->belongsTo(User::class, "user_id", "id");
    }

    function customer_suppliers(){
        return $this->belongsTo(CustomerSupplier::class,'customer_suppliers_id','id');
    }
}
