<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Contract
 *
 * @property int $id
 * @property string $name 合同名称
 * @property string|null $sn 合同号
 * @property string $sn_alias 对方合同号
 * @property string|null $sn_inner 内部合同编号
 * @property string $type 合同类型customer:客户合同、supplier:供应商合同
 * @property int $customer_supplier_id 结算公司id
 * @property string|null $begin_time 合同开始时间
 * @property string|null $end_time 合同结束时间
 * @property int $process0_user_id 合同草拟申请人id
 * @property int $process0_status 合同草拟(默认为0)0:未办理[保存状态],1:同意
 * @property string|null $process0_time 合同草拟时间,null时无申请时间
 * @property int|null $credit_time_type 信控基准日1:业务日期,2:开票日期,3:到港日期
 * @property int|null $credit_delay_type 延迟类型1:延迟月份,2:延后自然日数,3:延后工作日数
 * @property int|null $credit_delay_data 延后月份:延迟类型为1,1:次月,2:次月月底,3:次次月,4:次次月底,5:次次次月,6:次次次月底;延迟类型为2:表示延后自然日数;延迟类型为3:表示延后工作日数
 * @property int|null $credit_delay_data_data 延后月份结算日:当是次月、次次月、次次次月才有具体数据天
 * @property int|null $process1_user_id 商务会签人id
 * @property int $process1_status 商务会签状态0:未办理[保存状态],1:同意
 * @property string|null $process1_time 商务会签时间
 * @property int|null $process2_user_id 业务会签人id
 * @property int $process2_status 申请状态0:未办理,1:同意
 * @property string|null $process2_time 业务会签时间
 * @property int|null $process3_user_id 审批人id
 * @property int $process3_status 申请状态0:未办理,1:同意
 * @property string|null $process3_time 审批会签时间
 * @property int|null $process4_user_id 归档人id
 * @property int $process4_status 申请状态0:未办理,1:同意
 * @property string|null $process4_time 归档时间
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Attachment $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BusinessContract[] $business_contract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $businesses
 * @property-read \App\CustomerSupplier $clear_companies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ContractCustomerSupplier[] $contract_customer_suppliers_hasMany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomerSupplier[] $customer_suppliers
 * @property-read \App\CustomerSupplier $is_self_customer_supplier
 * @property-read \App\User $process0_user
 * @property-read \App\User $process1_user
 * @property-read \App\User $process2_user
 * @property-read \App\User $process3_user
 * @property-read \App\User $process4_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReviewLog[] $review_logs
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Contract onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreditDelayData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreditDelayDataData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreditDelayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreditTimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess0Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess0Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess0UserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess1Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess1UserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess2Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess2Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess2UserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess3Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess3Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess3UserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess4Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess4Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereProcess4UserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereSnAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereSnInner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contract withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Contract withoutTrashed()
 * @mixin \Eloquent
 */
class Contract extends Model
{
    use SoftDeletes;

//    function contract_reviews()
//    {
//        return $this->hasMany('\App\ContractReview','contracts_id','id');
//    }
//    function clear_companies_has_many(){
//        return $this->hasMany('\App\ClearCompany','status','id');
//    }

//    function contract_data()
//    {
//        return $this->hasMany('\App\ContractData', 'contract_id', 'id');
//    }
    function business_contract()
    {
        return $this->hasMany('\App\BusinessContract', 'contract_id', 'id');
    }

    function clear_companies()
    {
        return $this->hasOne('\App\ClearCompany', 'id', 'clear_company_id');
    }

    //结算公司
    function is_self_customer_supplier()
    {
        return $this->belongsTo(CustomerSupplier::class, 'customer_supplier_id', 'id')->where("is_self", 1);
    }

//    function part_a_customer_suppliers(){
//        return $this->hasOne('\App\CustomerSupplier','id','part_a_customer_supplier_id');
//    }
//    function part_b_customer_suppliers(){
//        return $this->hasOne('\App\CustomerSupplier','id','part_b_customer_supplier_id');
//    }
//    function part_c_customer_suppliers(){
//        return $this->hasOne('\App\CustomerSupplier','id','part_c_customer_supplier_id');
//    }

    function review_logs()
    {
        $str = join('","', array_values(\Config::get('constants.REVIEW')));
        return $this->hasMany(ReviewLog::class, 'foreign_key', 'id')
            ->where('model', 'contracts')
            ->orderByRaw(" field(name,\"{$str}\") asc , updated_at asc");
    }

//    function customer_suppliers_belongsToMany(){
//        return $this->hasMany(CustomerSupplier::class);
//        return $this->belongsToMany(CustomerSupplier::class);
//    }

    function contract_customer_suppliers()
    {
        return $this->customer_suppliers();
    }

    function customer_suppliers()
    {
        return $this->belongsToMany(CustomerSupplier::class);//->withTimestamps();
    }

    function contract_customer_suppliers_hasMany()
    {
        return $this->hasMany(ContractCustomerSupplier::class);
    }

    function process0_user()
    {
        return $this->hasOne(User::class, "id", "process0_user_id");
    }

    function process1_user()
    {
        return $this->hasOne(User::class, "id", "process1_user_id");
    }

    function process2_user()
    {
        return $this->hasOne(User::class, "id", "process2_user_id");
    }

    function process3_user()
    {
        return $this->hasOne(User::class, "id", "process3_user_id");
    }

    function process4_user()
    {
        return $this->hasOne(User::class, "id", "process4_user_id");
    }

    function attachments()
    {
        return $this->belongsTo(Attachment::class, "id", "foreign_key")->where("model", "contracts");
    }

    function businesses()
    {
        return $this->belongsToMany(Business::class, 'business_contract', 'contract_id', 'slaver_business_id');
    }
}
