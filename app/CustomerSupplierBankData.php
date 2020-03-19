<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CustomerSupplierBankData
 *
 * @property int $id
 * @property int $customer_supplier_id
 * @property string $name 银行名称
 * @property string $account 银行账号
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $master_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $segment_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $slaver_businesses
 * @property-read \App\User $users
 * @property string|null $currency 币种
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerSupplierBankData whereCurrency($value)
 */
class CustomerSupplierBankData extends Model
{
    function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function segment_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_bank_data', 'customer_supplier_id', 'segment_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function master_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_bank_data', 'customer_supplier_id', 'master_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function slaver_businesses()
    {
        return $this->belongsToMany(Business::class, 'customer_supplier_ship_data_data', 'customer_supplier_id', 'slaver_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }
}
