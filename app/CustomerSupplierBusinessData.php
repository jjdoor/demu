<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CustomerSupplierBusinessData
 *
 * @property int $id
 * @property int $customer_supplier_id
 * @property int $segment_business_id
 * @property int $master_business_id
 * @property int $slaver_business_id
 * @property int|null $charge_rule_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereChargeRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $is_lock 0:锁定,不能编辑,1:可编辑
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereIsLock($value)
 * @property int $user_id
 * @property-read \App\Business $master_business
 * @property-read \App\Business $segment_business
 * @property-read \App\Business $slaver_business
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBusinessData whereUserId($value)
 */
class CustomerSupplierBusinessData extends Model
{
    function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function segment_business()
    {
        return $this->belongsTo(Business::class, "segment_business_id", 'id');
    }

    function master_business()
    {
        return $this->belongsTo(Business::class, "master_business_id", 'id');
    }

    function slaver_business()
    {
        return $this->belongsTo(Business::class, "slaver_business_id", 'id');
    }
}
