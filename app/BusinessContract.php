<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\BusinessContract
 *
 * @property int $id
 * @property int $contract_id
 * @property int $segment_business_id 业务板块id
 * @property int $master_business_id 主业务类型id
 * @property int $slaver_business_id 子业务类型id
 * @property int $charge_rule_id 价格协议号，其实就是收费规则
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ChargeRule $charge_rules
 * @property-read \App\Business $master_business
 * @property-read \App\Business $segment_business
 * @property-read \App\Business $slaver_business
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereChargeRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessContract whereUserId($value)
 * @mixin \Eloquent
 */
class BusinessContract extends Pivot
{
    protected $table = 'business_contract';

    function segment_business()
    {
        return $this->hasOne('\App\Business', 'id', 'segment_business_id');
    }

    function master_business()
    {
        return $this->hasOne('\App\Business', 'id', 'master_business_id');
    }

    function slaver_business()
    {
        return $this->hasOne('\App\Business', 'id', 'slaver_business_id');
    }

    //fixme-benjamin
    function charge_rules()
    {
        return $this->hasOne('\App\ChargeRule', 'id', 'charge_rule_id');
    }
}
