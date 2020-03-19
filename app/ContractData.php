<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * App\ContractData
 *
 * @property int $id
 * @property int $contract_id
 * @property int $segment_business_id 业务板块id
 * @property int $master_business_id 主业务类型id
 * @property int $slaver_business_id 子业务类型id
 * @property int $charge_rule_id 价格协议号，其实就是收费规则
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ChargeRule $charge_rules
 * @property-read \App\Business $master_businesses
 * @property-read \App\Business $segment_businesses
 * @property-read \App\Business $slaver_businesses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereChargeRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractData extends Model
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
    //fixme-benjamin
    function charge_rules(){
        return $this->hasOne('\App\ChargeRule','id','charge_rule_id');
    }
}
