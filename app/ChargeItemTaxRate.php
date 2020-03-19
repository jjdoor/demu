<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * This is the model class for table "charge_item_tax_rates".
 *
 * @property string $id
 * @property int $segment_business_id 业务板块,level-0
 * @property int $master_business_id 主业务板块,level-1
 * @property int $slaver_business_id 子业务板块
 * @property int $charge_item_id 费用科目
 * @property int $invoice_type_id 开票类型id
 * @property int $is_tax_free 是否免税
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Business $segment_businesses
 * @property Business $master_businesses
 * @property Business $slaver_businesses
 * @property ChargeItem $charge_items
 * @property InvoiceType $invoice_types
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereChargeItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereInvoiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereIsTaxFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeItemTaxRate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\CustomerSupplier $customer_suppliers
 * @property-read \App\User $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeItemTaxRate onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeItemTaxRate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeItemTaxRate withoutTrashed()
 */
class ChargeItemTaxRate extends Model
{
//    protected $casts = [
//        'updated_at' => 'datetime',
//    ];

    use SoftDeletes;

    function segment_businesses(){
        return $this->hasOne('\App\Business','id','segment_business_id');
    }

    function master_businesses(){
        return $this->hasOne('\App\Business','id','master_business_id');
    }

    function slaver_businesses(){
        return $this->hasOne('\App\Business','id','slaver_business_id');
    }

    function charge_items(){
        return $this->hasOne('\App\ChargeItem','id','charge_item_id');
    }

    function customer_suppliers(){
        return $this->hasOne(CustomerSupplier::class,'id','customer_suppliers_id');
    }
    function invoice_types(){
        return $this->hasOne('\App\InvoiceType','id','invoice_type_id');
    }
    function users(){
        return $this->belongsTo(User::class,"user_id","id");
    }
}
