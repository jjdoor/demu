<?php
/**
 * Author: jkwu
 * Date: 2019/8/24
 * Time: 15:20
 * Description:
 */

namespace App\Http\Controllers;

use App\Business;
use App\ChargeItem;
use App\ChargeItemTaxRate;
use App\ChargeRule;
use App\ChargeRulesData;
use App\ChargeRulesExpression;
use App\ContainerAddress;
use App\ContainerType;
use App\CustomerSupplier;
use App\CustomerSupplierBusinessData;
use App\InvoiceType;
use App\Port;
use App\PriceMaintain;
use App\PriceMaintainData;
use App\Product;
use App\User;
use Illuminate\Http\Request;

/**
 * @group 价格协议管理031
 */
class ChargeRuleController extends Controller
{
    /**
     * 列表03101
     *
     * @queryParam page 第几页，默认为1
     * @queryParam per_page 每页数，默认为10
     * @queryParam status 状态 0禁用，1启用
     * @queryParam verify_status 审核状态 0未审核，1已审核
     * @queryParam search_id 价格协议id
     * @queryParam name_keyword 协议号搜索关键词
     * @queryParam title_keyword 协议内容搜索关键词
     * @queryParam customer_suppliers_id 结算公司
     * @queryParam segment_business_id 业务板块
     * @queryParam master_business_id 主业务板块
     * @queryParam slaver_business_id 子业务板块
     * @queryParam create_user_id 创建人id
     * @queryParam c_start_time  数据创建开始时间
     * @queryParam c_end_time  数据创建结束时间
     * @queryParam update_user_id 修改人id
     * @queryParam u_start_time  数据修改开始时间
     * @queryParam u_end_time  数据修改结束时间
     * @queryParam verify_user_id 审核人id
     * @queryParam v_start_time  数据审核开始时间
     * @queryParam v_end_time  数据审核结束时间
     * @response {
     * "data":[{
     *       "id": 4,
     *       "status": "状态",
     *       "verify_status": "审核状态",
     *       "name": "价格协议号",
     *       "agreement_title": "价格协议内容",
     *       "customer_suppliers_name": "结算公司",
     *       "segment_business_name": "业务板块",
     *       "master_business_name": "主业务类型",
     *       "slaver_business_name": "子业务类型",
     *       "create_user_name": "创建人",
     *       "update_user_name": "操作人",
     *       "verify_user_name": "审核人",
     *       "created_at": "创建时间",
     *       "updated_at": "修改时间",
     *       "verify_time": "审核时间",
     *
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=5",
     *  "next_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=2",
     *  "path": "http://demu.tao3w.com/api/v1/chargeItemTaxRates",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @param Request $request
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function index(Request $request)
    {
        $list = ChargeRule::query()->with(['create_users', 'update_users', 'verify_users', 'segment_businesses', 'master_businesses', 'slaver_businesses', 'customer_suppliers'])
            ->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
                $q->where('segment_business_id', $request->input('segment_business_id'));
            })
            ->when($request->input('master_business_id') !== null, function ($q) use ($request) {
                $q->where('master_business_id', $request->input('master_business_id'));
            })
            ->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
            })
            ->when($request->input('search_id') !== null, function ($q) use ($request) {
                $q->where('id', $request->input('search_id'));
            })
            ->when($request->input('name_keyword') !== null, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('name_keyword')}%");
            })
            ->when($request->input('title_keyword') !== null, function ($q) use ($request) {
                $q->where('agreement_title', 'like', "%{$request->input('title_keyword')}%");
            })
            ->when($request->input('status') !== null, function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            })
            ->when($request->input('verify_status') !== null, function ($q) use ($request) {
                $q->where('verify_status', $request->input('verify_status'));
            })
            ->when($request->input('customer_suppliers_id') !== null, function ($q) use ($request) {
                $q->where('customer_suppliers_id', $request->input('customer_suppliers_id'));
            })
            ->when($request->input('create_user_id') !== null, function ($q) use ($request) {
                $q->where('create_user_id', $request->input('create_user_id'));
            })
            ->when($request->input('c_start_time') !== null, function ($q) use ($request) {
                $q->where('created_at', '>', $request->input('c_start_time'));
            })
            ->when($request->input('c_end_time') !== null, function ($q) use ($request) {
                $q->where('created_at', '<', $request->input('c_end_time'));
            })
            ->when($request->input('update_user_id') !== null, function ($q) use ($request) {
                $q->where('update_user_id', $request->input('update_user_id'));
            })
            ->when($request->input('u_start_time') !== null, function ($q) use ($request) {
                $q->where('updated_at', '>', $request->input('u_start_time'));
            })
            ->when($request->input('u_end_time') !== null, function ($q) use ($request) {
                $q->where('updated_at', '<', $request->input('u_end_time'));
            })
            ->when($request->input('verify_user_id') !== null, function ($q) use ($request) {
                $q->where('verify_user_id', $request->input('verify_user_id'));
            })
            ->when($request->input('v_start_time') !== null, function ($q) use ($request) {
                $q->where('verify', '>', $request->input('v_start_time'));
            })
            ->when($request->input('v_end_time') !== null, function ($q) use ($request) {
                $q->where('verify', '<', $request->input('v_end_time'));
            })
            ->orderBy("updated_at", "desc")->paginate($request->input('per_page', 10));


        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            $data = [
                'id' => $list->id,
                'status' => $list->status ?? '',
                'verify_status' => $list->verify_status ?? '',
                'name' => $list->name ?? '',
                'agreement_title' => $list->agreement_title ?? '',
                'customer_suppliers_name' => $list->customer_suppliers->name ?? '',
                'segment_business_name' => $list->segment_businesses->name ?? '',
                'master_business_name' => $list->master_businesses->name ?? '',
                'slaver_business_name' => $list->slaver_businesses->name ?? '',
                'create_user_name' => $list->create_users->name ?? '',
                'update_user_name' => $list->update_users->name ?? '',
                'verify_user_name' => $list->verify_users->name ?? '',
                'created_at' => (string)$list->created_at ?? '',
                'updated_at' => (string)$list->updated_at ?? '',
                'verify_time' => (string)$list->verify_time ?? '',
            ];
            return $data;
        }))->toArray();

        //创建人
        $c_user_arr = ChargeRule::with(['create_users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'create_users.id');
            $return['value'] = data_get($item, 'create_users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
        //修改人
        $u_user_arr = ChargeRule::with(['update_users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'update_users.id');
            $return['value'] = data_get($item, 'update_users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
        //审核人
        $v_user_arr = ChargeRule::with(['verify_users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'verify_users.id');
            $return['value'] = data_get($item, 'verify_users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        //获取charge_rules表数据
        $charge_rules_arr = ChargeRule::query()->select('id', 'name', 'agreement_title')->get()->map(function ($item) {
            $return1 = [];
            $return1['id'] = data_get($item, 'id');
            $return1['name'] = data_get($item, 'name');
            $return1['agreement_title'] = data_get($item, 'agreement_title');
            return $return1;
        });
        //获取结算公司数据
        $customer_suppliers_arr = CustomerSupplier::query()->select('id', 'name_abbreviation')->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'id');
            $return['value'] = data_get($item, 'name_abbreviation');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        //状态
        $status_arr = [['key' => 0, 'value' => '禁用'], ['key' => 1, 'value' => '启用']];
        //审核状态
        $verify_status_arr = [['key' => 0, 'value' => '未审核'], ['key' => 1, 'value' => '已审核']];
        return array_merge($collection, ['create_user' => $c_user_arr], ['update_user' => $u_user_arr], ['verify_user' => $v_user_arr], ['status' => $status_arr], ['verify_status' => $verify_status_arr], ['charge_rule_list' => $charge_rules_arr], ['customer_suppliers_list' => $customer_suppliers_arr]);

    }

    /**
     * 启禁用状态更新03102
     *
     * @queryParam ids required 多个用英文逗号分割
     * @queryParam status required 0:禁用，1：启用
     * @response 200{
     * }
     * @param chargeRule chargeRule
     * @return array
     */
    public function statusUpdate(Request $request)
    {

        $messages = [
            'ids . ids_check' => 'id输入错误',
        ];
        $rules = [
            'ids' => 'required | ids_check',
        ];
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            $explode = explode(",", $value);
            if ($attribute !== 'ids') {
                return false;
            }
            if (ChargeRule::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        ChargeRule::query()->whereIn("id", $ids_arr)->update(['status' => $request->input('status')]);

        return [];
    }

    /**
     * 审核状态更新03103
     *
     * @queryParam ids required 多个用英文逗号分割
     * @queryParam status required 0:未审核，1：已审核
     * @response 200{
     * }
     * @param chargeRule chargeRule
     * @return array
     */
    public function statusVerify(Request $request)
    {

        $messages = [
            'ids . ids_check' => 'id输入错误',
        ];
        $rules = [
            'ids' => 'required | ids_check',
        ];
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            $explode = explode(",", $value);
            if ($attribute !== 'ids') {
                return false;
            }
            if (ChargeRule::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        ChargeRule::query()->whereIn("id", $ids_arr)->update(['verify_status' => $request->input('status'), 'verify_user_id' => $request->get('login_user_id'), 'verify_time' => date("Y-m-d H:i:s")]);
        //记录审核log
        $chargeRulesData = new ChargeRulesData();
        foreach ($ids_arr as $value)
            $chargeRulesData->charge_rules_id = $value;
        $chargeRulesData->status = $request->input('status');
        $chargeRulesData->user_id = $request->get('login_user_id');
        $chargeRulesData->save();
        return [];
    }


    /**
     * 列表删除03104
     * @queryParam ids required 价格协议id 多个用英文逗号分割
     * @response {
     * }
     * @param request $request
     * @return array
     */
    public function destroy(Request $request)
    {

        $ids = $request->input('ids');
        $ids_arr = explode(",", $ids);
        foreach ($ids_arr as $value) {
            //删除chargeitem表数据
            ChargeRule::destroy($value);
            //删除chargeitemtaxData数据
            chargeRulesData::query()->where("charge_rules_id", $value)->delete();
        }
        return [];
    }

    /**
     * 价格协议编辑03105
     * @queryParam id required 修改传id
     * @queryParam name required 价格协议号
     * @queryParam agreement_title required 价格协议内容
     * @queryParam customer_suppliers_id required 结算公司id
     * @queryParam segment_business_id required 业务板块
     * @queryParam master_business_id required  主业务板块
     * @queryParam slaver_business_id required  子业务板块
     * @queryParam status required  启禁用状态 0：禁用，1：启用
     * @queryParam charge_rule_data_list   required 收费规则list
     * @queryParam charge_rule_expression_list   required 收费规则价格条件
     *
     * @queryParam -------logical_operator required
     * @queryParam --------price_maintain_id required
     * @queryParam -------price_condition_name required
     * @queryParam ------- operator_symbol  required
     * @queryParam --------value_id required
     * @queryParam ------- value_name required
     *
     * @response {
     * }
     * @param request $request
     * @return array
     */
    public function chargeUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'between:1,50|required',
            'agreement_title' => 'between:1,30|required',
            'customer_suppliers_id' => 'integer|required',
            'segment_business_id' => 'integer|required',
            'master_business_id' => 'integer|required',
            'slaver_business_id' => 'integer|required',
            'status' => 'boolean|required',
        ]);
        //编辑价格协议
        $chargeRule = ChargeRule::find($request->input('id'));
        if (empty($chargeRule)) {
            $chargeRule = new ChargeRule();
            $chargeRule->create_user_id = $request->get('login_user_id');
        } else {
            $chargeRule->update_user_id = $request->get('login_user_id');
        }
        $chargeRule->name = $request->input('name');
        $chargeRule->agreement_title = $request->input('agreement_title');
        $chargeRule->customer_suppliers_id = $request->input('customer_suppliers_id');
        $chargeRule->segment_business_id = $request->input('segment_business_id');
        $chargeRule->master_business_id = $request->input('master_business_id');
        $chargeRule->slaver_business_id = $request->input('slaver_business_id');
        $chargeRule->status = $request->input('status');
        $chargeRule->save();
        //编辑价格协议收费规则
        $charge_rule_data_list = $request->input('charge_rule_data_list');
        foreach ($charge_rule_data_list as $key => $value) {

            $chargeRuleData = ChargeRulesData::find($value['id']);
            if (empty($chargeRuleData)) {
                $chargeRuleData = new ChargeRulesData();
            }
            $chargeRuleData->charge_rules_id = $chargeRule->id;
            $chargeRuleData->direction = $value['direction'] ?? null;
            $chargeRuleData->is_tax_free = $value['is_tax_free'] ?? null;
            $chargeRuleData->charge_item_id = $value['charge_item_id'] ?? null;
            $chargeRuleData->charge_item_name = $value['charge_item_name'] ?? null;
            $chargeRuleData->c_price_maintain_id = $value['c_price_maintain_id'] ?? null;
            $chargeRuleData->c_price_maintain_name = $value['c_price_maintain_name'] ?? null;
            $chargeRuleData->c_customer_suppliers_id = $value['c_customer_suppliers_id'] ?? null;
            $chargeRuleData->c_customer_suppliers_name = $value['c_customer_suppliers_name'] ?? null;
            $chargeRuleData->p_price_maintain_id = $value['p_price_maintain_id'] ?? null;
            $chargeRuleData->p_price_maintain_name = $value['p_price_maintain_name'] ?? null;
            $chargeRuleData->p_customer_suppliers_id = $value['p_customer_suppliers_id'] ?? null;
            $chargeRuleData->currency_code = $value['currency_code'] ?? null;
            $chargeRuleData->invoice_types_id = $value['invoice_types_id'] ?? null;
            $chargeRuleData->invoice_types_name = $value['invoice_types_name'] ?? null;
            $chargeRuleData->invoice_types_tax_rate = $value['invoice_types_tax_rate'] ?? null;
            $chargeRuleData->price_expression = $value['price_expression'] ?? null;
            $chargeRuleData->charge_unit_id = $value['charge_unit_id'] ?? null;
            $chargeRuleData->charge_unit = $value['charge_unit'] ?? null;
            $chargeRuleData->unit_price = $value['unit_price'] ?? null;
            $chargeRuleData->invoice_price_min = $value['invoice_price_min'] ?? null;
            $chargeRuleData->invoice_price_max = $value['invoice_price_max'] ?? null;
            $chargeRuleData->user_id = $request->get('login_user_id');
            $chargeRuleData->save();

            //费用规则价格条件编辑
            $charge_rule_expression_list = $request->input('charge_rule_expression_list');
            ChargeRulesExpression::query()->where("charge_rules_data_id", $chargeRuleData->id)->delete();
            foreach ($charge_rule_expression_list as $value1) {
                $ChargeRulesExpression = new ChargeRulesExpression();
                $ChargeRulesExpression->charge_rules_data_id = $chargeRuleData->id;
                $ChargeRulesExpression->logical_operator = $value1['logical_operator'];
                $ChargeRulesExpression->price_maintain_id = $value1['price_maintain_id'];
                $ChargeRulesExpression->price_condition_name = $value1['price_condition_name'];
                $ChargeRulesExpression->operator_symbol = $value1['operator_symbol'];
                $ChargeRulesExpression->value_id = $value1['value_id'];
                $ChargeRulesExpression->value_name = $value1['value_name'];
                $ChargeRulesExpression->save();
            }

        }
        return $chargeRule->id;
    }

    /**
     * 收费规则基础数据03106
     * @queryParam direction_id required 收付标志 1：收，2：付，3：收付
     * @queryParam is_tax_free required 是否免税 0：否，1:是
     * @queryParam segment_business_id required 业务板块
     * @queryParam master_business_id required  主业务板块
     * @queryParam slaver_business_id required  子业务板块
     * @queryParam invoice_types_id   开票类型
     * @response {
     * }
     * @param request $request
     * @return array
     */
    public function chargeRuleDataBase(Request $request)
    {
        $direction_id = $request->input('direction_id');
        $is_tax_free = $request->input('is_tax_free');
        $segment_business_id = $request->input('segment_business_id');
        $master_business_id = $request->input('master_business_id');
        $slaver_business_id = $request->input('slaver_business_id');
        $invoice_types_id = $request->input('invoice_types_id');
        if ($direction_id == "" || $is_tax_free == "" || $segment_business_id == "" || $master_business_id == "" || $slaver_business_id == "") {
            return [];
        }

        //根据条件获取开票类型
        $invoice_type_arr = array();
        $new_invoice_key = array();
        $tax_rate_arr = ChargeItemTaxRate::query()->where(['is_tax_free' => $is_tax_free, 'segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
        foreach ($tax_rate_arr as $value) {
            if ($value['invoice_types_id'] != "") {
                $invoice_type1_arr = InvoiceType::query()->where(['id' => $value['invoice_types_id'], 'direction' => $direction_id, 'status' => 1])->select(['id', 'name', 'tax_rate'])->get()->toArray();
                if (!empty($invoice_type1_arr)) {
                    $new_invoice_key[] = $invoice_type1_arr[0]['id'];
                    if (isset($new_invoice_key[$invoice_type1_arr[0]['id']]) == false) {
                        $invoice_type['key'] = $invoice_type1_arr[0]['id'];
                        $invoice_type['value'] = $invoice_type1_arr[0]['name'];
                        $invoice_type['tax_rate'] = $invoice_type1_arr[0]['tax_rate'];
                        $invoice_type_arr[] = $invoice_type;
                    }
                }
            }
        }
        //费用科目
        $charge_item_arr = array();
        $new_key = array();
        if ($invoice_types_id != "") {
            $charge_items_invoice_arr = ChargeItemTaxRate::query()->where(['invoice_types_id' => $invoice_types_id])->get()->toArray();
            if (!empty($charge_items_invoice_arr)) {
                foreach ($charge_items_invoice_arr as $value) {
                    if ($value['charge_item_id'] != "") {

                        $charge_item1_arr = ChargeItem::query()->where(['id' => $value['charge_item_id'], 'status' => 1])->select(['id', 'code', 'name'])->get()->toArray();

                        if (!in_array($charge_item1_arr[0]['id'], $new_key)) {
                            $new_key[] = $charge_item1_arr[0]['id'];
                            $charge_item['key'] = $charge_item1_arr[0]['id'];
                            $charge_item['code'] = $charge_item1_arr[0]['code'];
                            $charge_item['value'] = $charge_item1_arr[0]['name'];
                            $charge_item_arr[] = $charge_item;
                        }
                    }
                }
            }
        }

        //收付单位来源
        $price_maintain_id_arr = array();
        $price_maintain_arr = array();
        $charge_unit_arr = array();
        $price_maintain_data_arr = PriceMaintainData::query()->where(['segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
        if (!empty($price_maintain_data_arr)) {
            foreach ($price_maintain_data_arr as $value1) {
                $price_maintain_id_arr[] = $value1['price_maintain_id'];
            }

            if (!empty($price_maintain_id_arr)) {
                $price_maintain1_arr = PriceMaintain::query()->whereIn('id', $price_maintain_id_arr)->where('is_payment_source', 1)->select(['id', 'business_price'])->get()->toArray();
                foreach ($price_maintain1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['business_price'];
                    $price_maintain_arr[] = $value1;
                }
                //计费单位列表
                $charge_unit1_arr = PriceMaintain::query()->whereIn('id', $price_maintain_id_arr)->where('is_charging', 1)->select(['id', 'business_price'])->get()->toArray();
                foreach ($charge_unit1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['business_price'];
                    $charge_unit_arr[] = $value1;
                }
            }
        }
        //固定应收付单位
        $customer_supplier_id_arr = array();
        $customer_supplier_arr = array();
        $customer_supplier_data_arr = CustomerSupplierBusinessData::query()->where(['segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
        if (!empty($customer_supplier_data_arr)) {
            foreach ($customer_supplier_data_arr as $value2) {
                $customer_supplier_id_arr[] = $value2['customer_supplier_id'];
            }
            if (!empty($customer_supplier_id_arr)) {
                $customer_supplier1_arr = CustomerSupplier::query()->whereIn('id', $customer_supplier_id_arr)->where(['is_supplier' => 1, 'is_self' => 1])->select(['id', 'name'])->get()->toArray();
                foreach ($customer_supplier1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['name'];
                    $customer_supplier_arr[] = $value1;
                }
            }
        }
        //收付标志 1：收，2：付，3：收付
        $direction = [['key' => 1, 'value' => '收'], ['key' => 2, 'value' => '付'], ['key' => 3, 'value' => '收付']];
        //是否免税 0：否，1：是
        $is_tax_free_arr = [['key' => 0, 'value' => '否'], ['key' => 1, 'value' => '是']];
        //货币代码 1:USD,2:CNY
        $currency_code_arr = [['key' => 1, 'value' => 'USD'], ['key' => 2, 'value' => 'CNY']];
        return array_merge(['invoice_type_list' => $invoice_type_arr], ['charge_item_list' => $charge_item_arr], ['payment_source_list' => $price_maintain_arr], ['charge_unit_list' => $charge_unit_arr], ['customer_supplier_list' => $customer_supplier_arr], ['direction_list' => $direction], ['is_tax_free_list' => $is_tax_free_arr], ['currency_code_list' => $currency_code_arr]);
    }

    /**
     * 价格协议详情03107
     * @queryParam id required 价格协议id
     *
     * @response {
     * }
     * @param request $request
     * @return array
     */
    public function chargeRuleDetails($id)
    {
        if (empty($id)) {
            return [];
        }
        //-------价格协议主体数据--------
        global $charge_rule_list;
        $charge_rule_list = ChargeRule::query()->where(['id' => $id])->get();
        //对数据处理
        $charge_rule_arr = $charge_rule_list->map(function ($charge_rule_list) {
            //获取业务数据
            $business_arr = Business::query()->get(['id', 'name'])->toArray();
            foreach ($business_arr as $value) {

                if ($charge_rule_list->segment_business_id == $value['id']) {
                    $segment_businesses_name = $value['name'];
                }
                if ($charge_rule_list->master_business_id == $value['id']) {
                    $master_businesses_name = $value['name'];
                }
                if ($charge_rule_list->slaver_business_id == $value['id']) {
                    $slaver_businesses_name = $value['name'];
                }

            }
            //获取业务板块
            $segment_business_list = Business::query()->where("parent_id", 0)->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            //获取主业务板块
            $master_business_list = Business::query()->where("parent_id", $charge_rule_list->segment_business_id)->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            //获取子业务板块
            $slaver_business_list = Business::query()->where("parent_id", $charge_rule_list->master_business_id)->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            //获取客户供应商
            global $name_abbreviation;
            $customer_supplier_arr = CustomerSupplier::query()->where(['is_self' => 1])->select(['id', 'name_abbreviation'])->get()->map(function ($item) use ($charge_rule_list) {
                if (data_get($item, 'id') == $charge_rule_list->customer_suppliers_id) {
                    global $name_abbreviation;
                    $name_abbreviation = data_get($item, 'name_abbreviation');
                }
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name_abbreviation');
                return $return;
            });

            //获取用户
            $user_arr = User::query()->select(['id', 'name'])->get();
            foreach ($user_arr as $value) {
                if ($charge_rule_list->create_user_id == $value['id']) {
                    $create_user_name = $value['name'];
                }
                if ($charge_rule_list->update_user_id == $value['id']) {
                    $update_user_name = $value['name'];
                }
                if ($charge_rule_list->verify_user_id == $value['id']) {
                    $verify_user_name = $value['name'];
                }
            }

            $data = [
                'id' => $charge_rule_list->id,
                'name' => $charge_rule_list->name,
                'agreement_title' => $charge_rule_list->agreement_title,
                'segment_business_id' => $charge_rule_list->segment_business_id,
                'segment_business_name' => $segment_businesses_name ?? null,
                'segment_business_list' => $segment_business_list,
                'master_business_id' => $charge_rule_list->master_business_id,
                'master_business_name' => $master_businesses_name ?? null,
                'master_business_list' => $master_business_list,
                'slaver_business_id' => $charge_rule_list->slaver_business_id,
                'slaver_business_name' => $slaver_businesses_name ?? null,
                'slaver_business_list' => $slaver_business_list,
                'customer_suppliers_id' => $charge_rule_list->customer_suppliers_id ?? null,
                'name_abbreviation' => $name_abbreviation ?? null,
                'customer_supplier_list' => $customer_supplier_arr,
                'verify_status' => $charge_rule_list->verify_status,
                'create_user_name' => $create_user_name ?? null,
                'update_user_name' => $update_user_name ?? null,
                'verify_user_name' => $verify_user_name ?? null,
                'created_at' => (string)$charge_rule_list->created_at,
                'updated_at' => (string)$charge_rule_list->updated_at ?? null,
                'verify_time' => (string)$charge_rule_list->verify_time ?? null,
            ];
            return $data;
        })->toArray();

        //--------收费规则列表---------
        $charge_rule_data = chargeRulesData::query()->where(['charge_rules_id' => $id])->get();
        $charge_rule_data_arr = $charge_rule_data->map(function ($charge_rule_data) use ($charge_rule_arr) {

            $customer_suppliers_id = $charge_rule_arr[0]['customer_suppliers_id'];
            $segment_business_id = $charge_rule_arr[0]['segment_business_id'];
            $master_business_id = $charge_rule_arr[0]['master_business_id'];
            $slaver_business_id = $charge_rule_arr[0]['slaver_business_id'];
            $is_tax_free = $charge_rule_data->is_tax_free; //是否免税
            $direction = $charge_rule_data->direction; //收付标志
            //根据条件获取开票类型
            $tax_rate_arr = ChargeItemTaxRate::query()->where(['is_tax_free' => $is_tax_free, 'customer_suppliers_id' => $customer_suppliers_id, 'segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
            $invoice_type_arr = array();
            $charge_item_arr = array();
            foreach ($tax_rate_arr as $value) {
                if ($value['invoice_types_id'] != "") {
                    //开票类型
                    $invoice_type1_arr = InvoiceType::query()->where(['id' => $value['invoice_types_id'], 'direction' => $direction, 'status' => 1])->select(['id', 'name', 'tax_rate'])->get()->toArray();
                    foreach ($invoice_type1_arr as $key => $value) {
                        $value1['key'] = $value['id'];
                        $value1['value'] = $value['name'];
                        $value1['tax_rate'] = $value['tax_rate'];
                        $invoice_type_arr[] = $value1;
                    }
                    //费用科目
                    $charge_item1_arr = ChargeItem::query()->where(['id' => $value['charge_item_id'], 'status' => 1])->select(['id', 'code', 'name'])->get()->toArray();
                    foreach ($charge_item1_arr as $key => $value) {
                        $value1['key'] = $value['id'];
                        $value1['value'] = $value['name'];
                        //$value1['code']=$value['code'];
                        $charge_item_arr[] = $value1;
                    }
                }
            }
            //收付单位来源
            $price_maintain_id_arr = array();
            $price_maintain_arr = array();
            $charge_unit_arr = array();
            $price_maintain_data_arr = PriceMaintainData::query()->where(['segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
            foreach ($price_maintain_data_arr as $value1) {
                $price_maintain_id_arr[] = $value1['price_maintain_id'];
            }
            if (!empty($price_maintain_id_arr)) {
                $price_maintain1_arr = PriceMaintain::query()->whereIn('id', $price_maintain_id_arr)->where('is_payment_source', 1)->select(['id', 'business_price'])->get()->toArray();
                foreach ($price_maintain1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['business_price'];
                    $price_maintain_arr[] = $value1;
                }
                //计费单位列表
                $charge_unit1_arr = PriceMaintain::query()->whereIn('id', $price_maintain_id_arr)->where('is_charging', 1)->select(['id', 'business_price'])->get()->toArray();
                foreach ($charge_unit1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['business_price'];
                    $charge_unit_arr[] = $value1;
                }
            }
            //固定应收付单位
            $customer_supplier_id_arr = array();
            $customer_supplier_arr = array();
            $customer_supplier_data_arr = CustomerSupplierBusinessData::query()->where(['segment_business_id' => $segment_business_id, 'master_business_id' => $master_business_id, 'slaver_business_id' => $slaver_business_id])->get()->toArray();
            foreach ($customer_supplier_data_arr as $value2) {
                $customer_supplier_id_arr[] = $value2['customer_supplier_id'];
            }
            if (!empty($customer_supplier_id_arr)) {
                $customer_supplier1_arr = CustomerSupplier::query()->whereIn('id', $customer_supplier_id_arr)->where(['is_supplier' => 1, 'is_self' => 1])->select(['id', 'name'])->get()->toArray();
                foreach ($customer_supplier1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['name'];
                    $customer_supplier_arr[] = $value1;
                }
            }

            $rule_data = [
                'id' => $charge_rule_data->id,
                'direction' => $charge_rule_data->direction ?? null,
                'is_tax_free' => $charge_rule_data->is_tax_free ?? null,
                'invoice_types_tax_rate' => $charge_rule_data->invoice_types_tax_rate ?? null,
                'invoice_types_id' => $charge_rule_data->invoice_types_id ?? null,
                'invoice_types_name' => $charge_rule_data->invoice_types_name ?? null,
                'invoice_type_list' => $invoice_type_arr,         //开票类型
                'charge_item_id' => $charge_rule_data->charge_item_id ?? null,
                'charge_item_name' => $charge_rule_data->charge_item_name ?? null,
                'charge_item_list' => $charge_item_arr,           //费用科目
                'payment_source_list' => $price_maintain_arr,      //应收付单位来源
                'customer_supplier_list' => $customer_supplier_arr, //固定应收付单位
                'currency_code' => $charge_rule_data->currency_code ?? null,
                'c_price_maintain_id' => $charge_rule_data->c_price_maintain_id ?? null,
                'c_price_maintain_name' => $charge_rule_data->c_price_maintain_name ?? null,
                'c_customer_suppliers_id' => $charge_rule_data->c_customer_suppliers_id ?? null,
                'c_customer_suppliers_name' => $charge_rule_data->c_customer_suppliers_name ?? null,
                'p_price_maintain_id' => $charge_rule_data->p_price_maintain_id ?? null,
                'p_price_maintain_name' => $charge_rule_data->p_price_maintain_name ?? null,
                'p_customer_suppliers_id' => $charge_rule_data->p_customer_suppliers_id ?? null,
                'p_customer_suppliers_name' => $charge_rule_data->p_customer_suppliers_name ?? null,
                'price_expression' => $charge_rule_data->price_expression ?? null,
                'charge_unit_id' => $charge_rule_data->unit_price ?? null,
                'charge_unit' => $charge_rule_data->charge_unit ?? null,
                'charge_unit_list' => $charge_unit_arr, //计费单位
                'unit_price' => $charge_rule_data->unit_price ?? null,
                'invoice_price_min' => $charge_rule_data->invoice_price_min ?? null,
                'invoice_price_max' => $charge_rule_data->invoice_price_max ?? null,
            ];
            return $rule_data;

        })->toArray();

        //收付标志 1：收，2：付，3：收付
        $direction = [['key' => 1, 'value' => '收'], ['key' => 2, 'value' => '付'], ['key' => 3, 'value' => '收付']];
        //是否免税 0：否，1：是
        $is_tax_free_arr = [['key' => 0, 'value' => '否'], ['key' => 1, 'value' => '是']];
        //货币代码 1:USD,2:CNY
        $currency_code_arr = [['key' => 1, 'value' => 'USD'], ['key' => 2, 'value' => 'CNY']];

        return array_merge($charge_rule_arr, ['charge_rule_data_list' => $charge_rule_data_arr], ['direction_list' => $direction], ['is_tax_free_list' => $is_tax_free_arr], ['currency_code_list' => $currency_code_arr]);
    }

    /**
     * 收费规则条件03108
     * @queryParam id required 收费规则id
     *
     * @response {
     *      "data":[{
     *      "id":1,
     *      "charge_rules_data_id":"价格协议收费规则id",
     *      "logical_operator":"逻辑运算符 and，or",
     *      "price_maintain_id":"价格条件id",
     *      "price_condition_name":"价格条件名称",
     *      "operator_symbol":"操作符 1:等于,2:大于等于,2:大于,3:小于等于,4:小于,5:包含,6:不包含,7:不等于",
     *      "value_id":"条件值id",
     *      "value_name":"条件值名称",
     *  }]
     * }
     * @param request $request
     * @return array
     */
    public function chargeRuleExpression($id)
    {
        $data_list = ChargeRulesExpression::query()->where(['charge_rules_data_id' => $id])->get();
        //对数据处理
        $collection = $data_list->map(function ($data_list) {
            $data = [

                'id' => $data_list->id,
                'charge_rules_data_id' => $data_list->charge_rules_data_id ?? null,
                'logical_operator' => $data_list->logical_operator,
                'price_maintain_id' => $data_list->price_maintain_id,
                'price_condition_name' => $data_list->price_condition_name,
                'operator_symbol' => $data_list->operator_symbol,
                'value_id' => $data_list->value_id,
                'value_name' => $data_list->value_name,
            ];
            return $data;
        });
        return $collection;
    }

    /**
     * 费用规则新增修改03109
     * @queryParam id required 收费规则id
     * @queryParam charge_rules_id`  '价格协议表id',
     * @ueryParam direction   '收付标志 1：收，2：付，3：收付',
     * @ueryParam is_tax_free  是否免税 0：否，1:是',
     * @ueryParam charge_item_id '费用科目id',
     * @ueryParam charge_item_name'费用科目名称',
     * @ueryParam c_price_maintain_id '应收单位来源id',
     * @ueryParam c_price_maintain_name '应收单位来源名称',
     * @ueryParam c_customer_suppliers_id '固定应收单位id',
     * @ueryParam c_customer_suppliers_name '固定应收单位名称',
     * @ueryParam p_price_maintain_id '应付单位来源id',
     * @ueryParam p_price_maintain_name '应付单位来源名称',
     * @ueryParam p_customer_suppliers_id '固定应付单位id',
     * @ueryParam p_customer_suppliers_name '固定应付单位名称',
     * @ueryParam currency_code '货币代码 1:USD,2:CNY',
     * @ueryParam invoice_types_id '开票类型id',
     * @ueryParam invoice_types_name '开票类型',
     * @ueryParam invoice_types_tax_rate '开票税率',
     * @ueryParam price_expression '单价表达式',
     * @ueryParam charge_unit_id '计费单位id',
     * @ueryParam charge_unit '计费单位',
     * @ueryParam unit_price '单价',
     * @ueryParam invoice_price_min  '最小开票金额',
     * @ueryParam invoice_price_max '最大开票金额',
     * @ueryParam user_id  '用户id',
     * @response {
     * }
     * @param request $request id
     * @return array
     */
    public function chargeRuleDataUpdate(Request $request, $id)
    {
        $request_arr = $request->all();
        foreach ($request_arr as $value) {
            //修改数据使用实例
            $ChargeRulesData = ChargeRulesData::find($value['id']);
            if (empty($ChargeRulesData)) {
                //新增数据使用实例
                $ChargeRulesData = new ChargeRulesData();
            }
            $ChargeRulesData->charge_rules_id = $id;
            $ChargeRulesData->direction = $value['direction'] ?? null;
            $ChargeRulesData->is_tax_free = $value['is_tax_free'] ?? null;
            $ChargeRulesData->charge_item_id = $value['charge_item_id'] ?? null;
            $ChargeRulesData->charge_item_name = $value['charge_item_name'] ?? null;
            $ChargeRulesData->c_price_maintain_id = $value['c_price_maintain_id'] ?? null;
            $ChargeRulesData->c_customer_suppliers_name = $value['c_customer_suppliers_name'] ?? null;
            $ChargeRulesData->p_price_maintain_id = $value['p_price_maintain_id'] ?? null;
            $ChargeRulesData->p_price_maintain_name = $value['p_price_maintain_name'] ?? null;
            $ChargeRulesData->p_customer_suppliers_id = $value['p_customer_suppliers_id'] ?? null;
            $ChargeRulesData->p_customer_suppliers_name = $value['p_customer_suppliers_name'] ?? null;
            $ChargeRulesData->currency_code = $value['currency_code'] ?? null;
            $ChargeRulesData->invoice_types_id = $value['invoice_types_id'] ?? null;
            $ChargeRulesData->invoice_types_name = $value['invoice_types_name'] ?? null;
            $ChargeRulesData->invoice_types_tax_rate = $value['invoice_types_tax_rate'] ?? null;
            $ChargeRulesData->price_expression = $value['price_expression'] ?? null;
            $ChargeRulesData->charge_unit_id = $value['charge_unit_id'] ?? null;
            $ChargeRulesData->charge_unit = $value['charge_unit'] ?? null;
            $ChargeRulesData->unit_price = $value['unit_price'] ?? null;
            $ChargeRulesData->invoice_price_min = $value['invoice_price_min'] ?? null;
            $ChargeRulesData->invoice_price_max = $value['invoice_price_max'] ?? null;
            $ChargeRulesData->user_id = $request->get('login_user_id');
            $ChargeRulesData->save();
        }
        return [];
    }

    /**
     * 费用规则删除03110
     * @queryParam ids required 费用规则id
     * @response {
     * }
     * @param request $request id
     * @return array
     */
    public function chargeRuleDataDelete(Request $request)
    {
        $ids = $request->input('ids');
        $ids_arr = explode(",", $ids);
        foreach ($ids_arr as $value) {
            //删除ChargeRulesData表数据
            ChargeRulesData::destroy($value);
            //删除ChargeRulesExpression表数据
            ChargeRulesExpression::query()->where("charge_rules_data_id", $value)->delete();
        }
        return [];
    }

    /**
     * 费用规则-价格条件列表03111
     * @queryParam id required 收费规则id
     * @response {
     * }
     * @param request $request id
     * @return array
     */
    public function chargeRulesExpressionList($id)
    {
        if ($id == "") {
            return [];
        }
        //根据收费规则id获取charge_rules_expression数据
        $expression_data = ChargeRulesExpression::query()->where(['charge_rules_data_id' => $id])->select(['charge_rules_data_id', 'logical_operator', 'price_maintain_id', 'price_condition_name', 'operator_symbol', 'value_id', 'value_name'])->get();
        return $expression_data;
    }


    /**
     * 费用规则-价格条件编辑03112
     * @queryParam logical_operator required 逻辑运算符 and，or
     * @queryParam price_maintain_id  价格条件id
     * @queryParam price_condition_name   价格条件名称
     * @queryParam operator_symbol  操作符 1:等于,2:大于等于,3:大于,4:小于等于,5:小于,6:包含,7:不包含,8:不等于
     * @queryParam value_id  条件值id
     * @queryParam value_name  条件值名称
     * @response {
     * }
     * @param request $request id
     * @return array
     */
    public function chargeRulesExpressionUpdate(Request $request, $id)
    {
        //删除费用规则价格条件
        ChargeRulesExpression::query()->where("charge_rules_data_id", $id)->delete();
        $request_arr = $request->all();
        foreach ($request_arr as $value) {
            $ChargeRulesExpression = new ChargeRulesExpression();
            $ChargeRulesExpression->charge_rules_data_id = $id;
            $ChargeRulesExpression->logical_operator = $value['logical_operator'];
            $ChargeRulesExpression->price_maintain_id = $value['price_maintain_id'];
            $ChargeRulesExpression->price_condition_name = $value['price_condition_name'];
            $ChargeRulesExpression->operator_symbol = $value['operator_symbol'];
            $ChargeRulesExpression->value_id = $value['value_id'];
            $ChargeRulesExpression->value_name = $value['value_name'];
            $ChargeRulesExpression->save();
        }
        return [];
    }

    /**
     * 费用规则-价格条件筛选数据03113
     * @queryParam segment_business_id required 业务板块
     * @queryParam master_business_id required  主业务板块
     * @queryParam slaver_business_id required 子业务板块
     * @queryParam price_maintain_id required  价格条件id
     * @response {
     * }
     * @param request $request id
     * @return array
     */
    public function chargeRulesExpressionBase(Request $request)
    {
        $list = PriceMaintain::query()->with(['price_maintain_data' => function ($q) use ($request) {
            $q->with(['segment_businesses', 'master_businesses', 'slaver_businesses']);
        }])
            ->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
                $q->whereHas('price_maintain_data', function ($q) use ($request) {
                    $q->where('segment_business_id', $request->input('segment_business_id'));
                });
            })
            ->when($request->input('master_business_id') !== null, function ($q) use ($request) {
                $q->whereHas('price_maintain_data', function ($q) use ($request) {
                    $q->where('master_business_id', $request->input('master_business_id'));
                });
            })
            ->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
                $q->whereHas('price_maintain_data', function ($q) use ($request) {

                    $q->where('slaver_business_id', $request->input('slaver_business_id'));
                });
            })
            ->when($request->input('status') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('status', 1);
                });
            })
            ->when($request->input('is_price_conditions') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('is_price_conditions', 1);
                });
            })->orderBy("updated_at", "desc")->paginate($request->input(1, 100));

        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            $data = [
                'id' => $list->id,
                'business_price' => $list->business_price ?? '',
            ];
            return $data;
        }))->toArray();
        $price_condition = $collection['data'];
        $is_input = 0;
        $price_condition_value_arr = array();

        $price_maintain_id = $request->input('price_maintain_id');
        if ($price_maintain_id != "") {
            //箱型
            if ($price_maintain_id == 1) {
                // 1：小箱，2：大箱，3：特大箱，4：自然箱
                $container_type = array(0 => array('key' => 'c1', 'value' => '小箱'), 1 => array('key' => 'c2', 'value' => '大箱'), 2 => array('key' => 'c3', 'value' => '特大箱'));
                $price_condition_value1_arr = ContainerType::query()->select(['id', 'size'])->orderBy("category_id", "asc")->get()->toArray();

                foreach ($price_condition_value1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['size'];
                    $price_condition_value_arr[] = $value1;
                }
                $price_condition_value_arr = array_merge($container_type, $price_condition_value_arr);
            }

            //港口
            if (in_array($price_maintain_id, array('18', '19', '20'))) {
                $price_condition_value1_arr = Port::query()->where('status', 1)->select(['id', 'name', 'country'])->get()->toArray();
                foreach ($price_condition_value1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['name'] . "(" . $value['country'] . ")";
                    $price_condition_value_arr[] = $value1;
                }
            }

            //品名
            if ($price_maintain_id == 21) {
                $price_condition_value1_arr = Product::query()->where('status', 1)->select(['id', 'name'])->get()->toArray();
                foreach ($price_condition_value1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['name'];
                    $price_condition_value_arr[] = $value1;
                }
            }
            //装送还箱地点
            if (in_array($price_maintain_id, array('13', '14', '15'))) {
                $category_status = 0;
                if ($price_maintain_id == 14) {
                    $category_status = 1;
                } else if ($price_maintain_id == 15) {
                    $category_status = 2;
                }
                $price_condition_value1_arr = ContainerAddress::query()->where(['status' => 1, 'category_status' => $category_status])->select(['id', 'address'])->get()->toArray();
                foreach ($price_condition_value1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['address'];
                    $price_condition_value_arr[] = $value1;
                }
            }

            //1-委托人、2-船公司、3-订舱公司、4-换单公司、5-货代公司、6-车队、7-保险公司、8-仓储公司、9-铁路公司、10-开证公司、11-提箱公司、12-还箱公司、13-检测公司、14-消毒公司、15-蒸熏公司、16-理货公司、17-装卸公司、18-其他
            if (in_array($price_maintain_id, array('9', '10', '11', '12', '17', '27', '28', '29', '30', '31', '32', '33'))) {
                if ($price_maintain_id == 9) {
                    $logistics_role = 3;
                } else if ($price_maintain_id == 10) {
                    $logistics_role = 4;
                } else if ($price_maintain_id == 11) {
                    $logistics_role = 14;
                } else if ($price_maintain_id == 12) {
                    $logistics_role = 12;
                } else if ($price_maintain_id == 17) {
                    $logistics_role = 2;
                } else if ($price_maintain_id == 27) {
                    $logistics_role = 5;
                } else if ($price_maintain_id == 28) {
                    $logistics_role = 16;
                } else if ($price_maintain_id == 29) {
                    $logistics_role = 15;
                } else if ($price_maintain_id == 30) {
                    $logistics_role = 13;
                } else if ($price_maintain_id == 31) {
                    $logistics_role = 17;
                } else if ($price_maintain_id == 32) {
                    $logistics_role = 6;
                } else if ($price_maintain_id == 33) {
                    $logistics_role = 1;
                }

                $price_condition_value1_arr = CustomerSupplier::query()->where(['logistics_role' => $logistics_role])->select(['id', 'name_abbreviation'])->get()->toArray();
                foreach ($price_condition_value1_arr as $key => $value) {
                    $value1['key'] = $value['id'];
                    $value1['value'] = $value['name_abbreviation'];
                    $price_condition_value_arr[] = $value1;
                }
            }
            //需要填写数据

            if (in_array($price_maintain_id, array('2', '3', '5', '6', '7', '22', '23', '24'))) {
                $is_input = 1;
            }


        }
        //1:等于,2:大于等于,3:大于,4:小于等于,5:小于,6:包含,7:不包含,8:不等于
        $logic_arr = array(array('key' => 1, 'value' => '等于'),
            array('key' => 2, 'value' => '大于等于'),
            array('key' => 3, 'value' => '大于'),
            array('key' => 4, 'value' => '小于等于'),
            array('key' => 5, 'value' => '小于'),
            array('key' => 6, 'value' => '包含'),
            array('key' => 7, 'value' => '不包含'),
            array('key' => 8, 'value' => '不等于'),
        );

        $result_arr = array('price_condition_list' => $price_condition, 'price_value_list' => $price_condition_value_arr, 'logic_list' => $logic_arr, 'is_input' => $is_input);
        return $result_arr;
    }


}
