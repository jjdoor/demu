<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FreightForwarder
 *
 * @property int $id
 * @property string $sn 业务号
 * @property string|null $from 揽货性质,company:公司揽货,person:销售揽货
 * @property int|null $segment_business_id 业务板块
 * @property int|null $master_business_id 主业务类型
 * @property int|null $slaver_business_id 子业务类型
 * @property int|null $customer_supplier_id 客户供应商id
 * @property int|null $clear_company_id 结算单位id
 * @property int $created_user_id 操作人id
 * @property string $created_user_name 操作人
 * @property string|null $created_created_at 创建时间
 * @property int $service_user_id 客服id
 * @property string $service_user_name 客服
 * @property int $sale_user_id 销售id
 * @property string $sale_user_name 销售
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ClearCompany $clear_companies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contract[] $contracts
 * @property-read \App\CustomerSupplier $customer_suppliers
 * @property-read \App\Business $master_businesses
 * @property-read \App\Business $segment_businesses
 * @property-read \App\Business $slaver_businesses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereClearCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereCreatedCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereCreatedUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereSaleUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereSaleUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereServiceUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereServiceUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FreightForwarder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FreightForwarder extends Model
{
    function segment_businesses()
    {
        return $this->hasOne(Business::class, 'id', 'segment_business_id');
    }

    function master_businesses()
    {
        return $this->hasOne(Business::class, 'id', 'master_business_id');
    }

    function slaver_businesses()
    {
        return $this->hasOne(Business::class, 'id', 'slaver_business_id');
    }

    function customer_suppliers()
    {
        return $this->hasOne(CustomerSupplier::class, 'id', 'customer_supplier_id');
    }

    function clear_companies()
    {
        return $this->hasOne(ClearCompany::class, 'id', 'clear_company_id');
    }

    function contracts()
    {
        return $this->belongsToMany(Contract::class);
//        return $this->hasMany(Contract::class,'id','contract_id');
    }

//    function process_logs(){
//        $str = join('","',array_values(\Config::get('constants.FRIGHT_FORWARDER_PROCESS')));
//        return $this->hasMany(ProcessLog::class,'foreign_key','id')
//            ->where('model','freight_forwarders')
//            ->orderByRaw(" field(name,\"{$str}\") asc , updated_at asc");
//    }
//
//    function last_process_logs(){
//        $str = join('","',array_values(\Config::get('constants.FRIGHT_FORWARDER_PROCESS')));
//        return $this->hasOne(ProcessLog::class,'foreign_key','id')
//            ->where('model','freight_forwarders')
//            ->where('status',1)
//            ->orderByRaw(" field(name,\"{$str}\") desc , updated_at asc");
//    }
//    function mapInto(){
//
//    }
}
