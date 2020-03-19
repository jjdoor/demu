<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\CustomerSupplierShipDataData
 *
 * @property int $id
 * @property int $customer_supplier_ship_data_id
 * @property int $user_id
 * @property int|null $segment_business_id
 * @property int|null $master_business_id
 * @property int|null $slaver_business_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Business|null $master_businesses
 * @property-read \App\Business|null $segment_businesses
 * @property-read \App\Business|null $slaver_businesses
 * @property-read \App\User $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipDataData onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereCustomerSupplierShipDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipDataData whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipDataData withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipDataData withoutTrashed()
 * @mixin \Eloquent
 */
class CustomerSupplierShipDataData extends Model
{
    use SoftDeletes;

    function segment_businesses(){
        return $this->belongsTo('\App\Business','segment_business_id','id');
    }

    function master_businesses(){
        return $this->belongsTo('\App\Business','master_business_id','id');
    }

    function slaver_businesses(){
        return $this->belongsTo('\App\Business','slaver_business_id','id');
    }
    function users(){
        return $this->belongsTo('App\User','user_id');
    }
}
