<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\CustomerSupplierShipData
 *
 * @property int $id
 * @property int $parent_id
 * @property int $customer_supplier_id
 * @property string $name 第一层是船名，第二层是航次
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomerSupplierShipData[] $customer_supplier_ship_data
 * @property-read \App\CustomerSupplier $customer_suppliers
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipData onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipData withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\CustomerSupplierShipData withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomerSupplierShipDataData[] $customer_supplier_ship_data_data
 * @property-read \App\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierShipData whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $master_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $segment_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $slaver_businesses
 */
class CustomerSupplierShipData extends Model
{
    use SoftDeletes;

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => 1,
    ];

    function customer_suppliers()
    {
        return $this->belongsTo(CustomerSupplier::class, "customer_supplier_id", "id");
    }

//    function customer_supplier_ship_data(){
//        return $this->hasMany(CustomerSupplierShipData::class,'parent_id','id');
//    }

    function customer_supplier_ship_data_data()
    {
        return $this->hasMany(CustomerSupplierShipDataData::class);
    }

    function businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_ship_data_data', 'customer_supplier_ship_data_id', 'slaver_business_id');
    }

    function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function segment_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_ship_data_data', 'customer_supplier_ship_data_id', 'segment_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function master_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_ship_data_data', 'customer_supplier_ship_data_id', 'master_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function slaver_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_ship_data_data', 'customer_supplier_ship_data_id', 'slaver_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }
}
