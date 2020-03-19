<?php

namespace App\Http\Controllers;

use App\CustomerSupplier;
use App\CustomerSupplierBankData;
use App\CustomerSupplierBusinessData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * @group 客户供应商管理021
 */
class CustomerSupplierController extends Controller
{
    /**
     * 列表02101
     *
     * @bodyParam page int 第几页，默认为1 Example:1
     * @bodyParam per_page int 每页数，默认为10 Example:10
     * @bodyParam search 公司/个人(全程/简称/助记码)模糊搜索 Example:n
     * @bodyParam logistics_role int 物流角色id，可多选，中间用英文逗号分割，1-委托人、2-船公司、3-订舱公司、4-换单公司、5-货代公司、6-车队、7-保险公司、8-仓储公司、9-铁路公司、10-开证公司、11-提箱公司、12-还箱公司、13-检测公司、14-消毒公司、15-蒸熏公司、16-理货公司、17-装卸公司、18-其他 Example:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
     * @bodyParam is_lock 审核状态 0-未审核、1-已审核 Example:1
     * @bodyParam is_customer 客户标志 0-否、1-是 Example:1
     * @bodyParam is_supplier 供应商标志 0-否、1-是 Example:1
     * @bodyParam is_invoice 结算单位标志 0-否、1-是 Example:1
     * @bodyParam is_self 是否结算公司 0-否、1-是 Example:1
     * @bodyParam created_user_id int 创建人 Example:1
     * @bodyParam updated_user_id int 修改人 Example:1
     * @bodyParam lock_user_id  int 审核人 Example:1
     * @bodyParam segment_business_id  int 业务板块id Example:1
     * @bodyParam master_business_id  int 主业务板块id Example:1
     * @bodyParam slaver_business_id  int 子业务板块id Example:1
     * @response {
     * "data":[{
     *  "id": 4,
     *  "is_lock": "审批状态0-未审批、1-已审批",
     *  "name": "公司/个人全称",
     *  "name_abbreviation": "公司/个人简称",
     *  "name_code": "公司/个人助记码",
     *  "tax_identification_number": "纳税人识别号",
     *  "logistics_role": "物流角色id，多个的话，用英文逗号分割",
     *  "is_customer": "客户标志 0-否、1-是",
     *  "is_customer_alias": "客户标志 0-否、1-是",
     *  "is_supplier": "供应商标志 0-否、1-是",
     *  "is_supplier_alias": "供应商标志 0-否、1-是",
     *  "is_invoice": "结算单位标志 0-否、1-是",
     *  "is_invoice_alias": "结算单位标志 0-否、1-是",
     *  "is_self": "结算公司 0-否、1-是",
     *  "is_self_alias": "结算公司 0-否、1-是",
     *  "segment_business_name": "业务板块",
     *  "master_business_name": "主业务板块",
     *  "slaver_business_name": "子业务板块",
     *  "created_user_name": "创建人",
     *  "created_time": "创建时间2019-08-19 17:00:11",
     *  "updated_user_name": "修改人",
     *  "updated_time": "修改时间2019-08-20 15:47:32",
     *  "lock_user_name": "审核人",
     *  "lock_time": "审核时间2019-08-19 17:00:11",
     *  "created_user":[
     *      {
     *          "key":"创建人id",
     *          "value":"创建人名字"
     *      }
     *  ],
     * "updated_user":[
     *      {
     *          "key":"修改人id",
     *          "value":"修改人名字"
     *      }
     *  ],
     *  "lock_user":[
     *      {
     *          "key":"审核人id",
     *          "value":"审核人名字"
     *      }
     *  ]
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/customerSupplier?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/customerSupplier?page=5",
     *  "next_page_url": "http://host/api/v1/customerSupplier?page=2",
     *  "path": "http://host/api/v1/customerSupplier",
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
        $search = $request->input('search');//公司/个人(全程/简称/助记码)
        $logistics_role = $request->input('logistics_role');//物流角色
        $is_lock = $request->input('is_lock');//审核状态
        $is_customer = $request->input('is_customer');//客户标志
        $is_supplier = $request->input('is_supplier');//供应商标志
        $is_invoice = $request->input('is_invoice');//结算单位标志
        $is_self = $request->input('is_self');//结算公司
        $created_users_id = $request->input('created_user_id');//创建人
        $updated_users_id = $request->input('updated_user_id');//修改人
        $lock_users_id = $request->input('lock_user_id');//审核人
        $segment_business_id = $request->input('segment_business_id');//审核人
        $master_business_id = $request->input('master_business_id');//审核人
        $slaver_business_id = $request->input('slaver_business_id');//审核人
        $list = CustomerSupplier::query()->with([
            'segment_business',
            'master_business',
            'slaver_business'
        ])->when($search, function ($q) use ($search) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("name", "like", "%{$search}%")
                ->orWhere("name_abbreviation", "like", "%{$search}%")
                ->orWhere("name_code", "like", "%{$search}%");
        })->when($logistics_role, function ($q) use ($logistics_role) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->whereRaw("find_in_set({$logistics_role},logistics_role)");
        })->when($is_customer !== null, function ($q) use ($is_customer) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("is_customer", $is_customer);
        })->when($is_lock !== null, function ($q) use ($is_lock) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("is_lock", $is_lock);
        })->when($is_supplier !== null, function ($q) use ($is_supplier) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("is_supplier", $is_supplier);
        })->when($is_invoice !== null, function ($q) use ($is_invoice) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("is_invoice", $is_invoice);
        })->when($is_self !== null, function ($q) use ($is_self) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("is_self", $is_self);
        })->when($created_users_id, function ($q) use ($created_users_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("created_user_id", $created_users_id);
        })->when($updated_users_id, function ($q) use ($updated_users_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("updated_user_id", $updated_users_id);
        })->when($lock_users_id, function ($q) use ($lock_users_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            return $q->where("lock_user_id", $lock_users_id);
        })->when($segment_business_id, function ($q) use ($segment_business_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('segment_business', function ($q) use ($segment_business_id) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('segment_business_id', $segment_business_id);
            });
        })->when($master_business_id, function ($q) use ($master_business_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('master_business', function ($q) use ($master_business_id) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('master_business_id', $master_business_id);
            });
        })->when($slaver_business_id, function ($q) use ($slaver_business_id) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('slaver_business', function ($q) use ($slaver_business_id) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('slaver_business_id', $slaver_business_id);
            });
        })
            ->orderBy("id", "desc")->paginate($request->get('per_page', 10));

//        return $list;
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            /** @var CustomerSupplier $list */
            $logistics_role = collect(explode(",", $list->logistics_role))->map(function ($item) {
                return \Config::get('constants.LOGISTICS_ROLE.' . $item);
            })->join(",");
            $data = [
                'id' => $list->id,
                'is_lock' => data_get($list, 'is_lock'),
                'is_lock_alias' => data_get($list, 'is_lock') ? "已审核" : "未审核",
                'name' => $list->name ?? '',
                'name_abbreviation' => $list->name_abbreviation ?? '',
                'name_code' => $list->name_code ?? '',
                'tax_identification_number' => $list->tax_identification_number ?? '',
//                'logistics_role_id'=>$list->logistics_roAZle ?? "",
                'logistics_role' => $logistics_role ?? '',
                'is_customer' => $list->is_customer ?? '',
                'is_customer_alias' => data_get($list, "is_customer") ? "是" : '否',
                'is_supplier' => $list->is_supplier ?? '',
                'is_supplier_alias' => data_get($list, "is_supplier") ? "是" : '否',
                'is_invoice' => $list->is_invoice ?? '',
                'is_invoice_alias' => data_get($list, "is_invoice") ? "是" : '否',
                'is_self' => $list->is_self ?? '',
                'is_self_alias' => data_get($list, "is_self") ? "是" : '否',
                'segment_business_name' => collect(data_get($list, 'segment_business'))->map(function ($item) {
                    $return = [];
                    $return[] = $item->name;
                    return $return;
                })->collapse()->join(","),
                'master_business_name' => collect($list->master_business)->map(function ($item) {
                    $return = [];
                    $return[] = $item->name;
                    return $return;
                })->collapse()->join(","),
                'slaver_business_name' => collect($list->slaver_business)->map(function ($item) {
                    $return = [];
                    $return[] = $item->name;
                    return $return;
                })->collapse()->join(","),
                'created_user_name' => data_get($list, 'created_user_name'),
                'created_time' => data_get($list, 'created_time'),
                'updated_user_name' => data_get($list, 'updated_user_name'),
                'updated_time' => data_get($list, 'updated_time'),
                'lock_user_name' => data_get($list, 'lock_user_name'),
                'lock_time' => data_get($list, 'lock_time'),
            ];
            return $data;
        }))->toArray();

        $statics = CustomerSupplier::with(['users', 'updated_user', 'lock_user'])->get();
        $created_list = $statics->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
        $updated_list = $statics->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'updated_user.id');
            $return['value'] = data_get($item, 'updated_user.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
        $lock_list = $statics->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'lock_user.id');
            $return['value'] = data_get($item, 'lock_user.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        return array_merge($collection, ['created_user' => $created_list], ['updated_user' => $updated_list], ['lock_user' => $lock_list]);
    }

    /**
     * 插入/更新业务板块关联信息02110
     *
     * @jsonParam id int 船公司船名航次与业务板块类型关系id
     * @jsonParam segment_business_id int 业务板块id
     * @jsonParam master_business_id int 主业务id
     * @jsonParam slaver_business_id int 子业务id
     * @jsonParam is_lock int 是否锁定 Example:1
     *
     * @param Request $request
     * @param CustomerSupplier $customerSupplier
     * @return array
     */
    public function update_business(Request $request, CustomerSupplier $customerSupplier)
    {
        $json = $request->getContent();
        $arr = json_decode($json, true);
        if ($arr === null) {
            return ['code' => 422, 'message' => 'json格式错误'];
        }

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request, $customerSupplier) {
            $data = $request->getContent();
            $arr = json_decode($data, true);
            $ids = [];
            foreach ($arr as $k => $v) {
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id'], 'is_lock' => $v['is_lock']];
            }
            $customerSupplier->businesses()->withTimestamps()->sync($ids);
        });
        return [];
    }

    /**
     * 显示02106
     * @urlParams id required 客户供应商id
     * @response {
     *  "id":4,
     *  "name":"公司/个人全称",
     *  "name_abbreviation": "公司/个人简称",
     *  "name_code":"公司/个人助记码",
     *  "tax_identification_number":"公司纳税人识别号",
     *  "contact":"联系人",
     *  "id_card_number":"身份证号",
     *  "tel_area_code":"电话区号",
     *  "tel":"电话",
     *  "mobile":"手机号",
     *  "country_id":"国家自增id",
     *  "city_id":"城市自增id",
     *  "address":"地址",
     *  "email":"email",
     *  "logistics_role":[
     *      {
     *          "key":"物流角色id",
     *          "value":"物流角色名称",
     *          "is_selected":"0:未选中、1:选中"
     *      }
     *  ],
     *  "is_customer":"0-不是客户 1-是客户",
     *  "is_supplier": "0-不是供应商 1-是供应商",
     *  "is_invoice":"0-不是结算单位 1-是结算单位",
     *  "is_self":"0-不是结算公司 1-是结算公司",
     *  "bank_name":[
     *      {
     *          "name":"银行名称",
     *          "account":"银行账号"
     *      }
     * ],
     *  "pay_max_time":[{
     *      "key":15,
     *      "value":"15天内付款",
     *      "is_selected":1
     * },{
     *      "key":30,
     *      "value":"30天内付款",
     *      "is_selected":0
     * },{
     *      "key":45,
     *      "value":"45天内付款",
     *      "is_selected":1
     * },{
     *      "key":60,
     *      "value":"50天内付款",
     *      "is_selected":0
     * }],
     *  "receive_max_time":[{
     *      "key":15,
     *      "value":"15天内收款",
     *      "is_selected":1
     * },{
     *      "key":30,
     *      "value":"30天内收款",
     *      "is_selected":0
     * },{
     *      "key":45,
     *      "value":"45天内收款",
     *      "is_selected":1
     * },{
     *      "key":60,
     *      "value":"50天内收款",
     *      "is_selected":0
     * }],
     *  "credit_max_money":"信控金额，单位分",
     *  "credit_max_time":"信控宽限天数，单位天",
     *  "created_user_name":"创建人",
     *  "created_time":"创建时间",
     *  "updated_user_name":"修改人",
     *  "updated_time":"修改时间",
     *  "lock_user_name":"审核人",
     *  "lock_time":"审核时间",
     *  "customer_supplier_bank_data":[
     *      {
     *          "id":1,
     *          "name":"银行名称",
     *          "account":"银行账号",
     *          "currency":"CNY人民币 USD美元"
     *      }
     *  ]
     * }
     *
     * @param CustomerSupplier $customerSupplier
     * @return CustomerSupplier
     */
    public function show(CustomerSupplier $customerSupplier)
    {
        $customerSupplier = CustomerSupplier::query()->where('id', $customerSupplier->id)->with(['customer_supplier_bank_data'])->first();
        $logistics_role_refactor = collect(\Config::get('constants.LOGISTICS_ROLE'))->map(function ($item, $key) use ($customerSupplier) {
            $explode = explode(",", $customerSupplier->logistics_role);
            $data = [];
            $data['key'] = $key;
            $data['value'] = $item;
            $data['is_selected'] = in_array($key, $explode) ? 1 : 0;
            return $data;
        })->toArray();
        $customerSupplier->logistics_role = array_values($logistics_role_refactor);

        $receive_max_time_refactor = collect(\Config::get('constants.RECEIVE_MAX_TIME'))->map(function ($item, $key) use ($customerSupplier) {
            $data = [];
            $data['key'] = $key;
            $data['value'] = $item;
            $data['is_selected'] = $key == $customerSupplier->receive_max_time ? 1 : 0;
            return $data;
        })->toArray();
        $customerSupplier->receive_max_time = array_values($receive_max_time_refactor);

        $pay_max_time_refactor = collect(\Config::get('constants.PAY_MAX_TIME'))->map(function ($item, $key) use ($customerSupplier) {
            $data = [];
            $data['key'] = $key;
            $data['value'] = $item;
            $data['is_selected'] = $key == $customerSupplier->pay_max_time ? 1 : 0;
            return $data;
        })->toArray();
        $customerSupplier->pay_max_time = array_values($pay_max_time_refactor);

        return $customerSupplier;
    }

    /**
     * 业务板块关联信息02111
     * @urlParam id required 客户供应商id
     * @response {
     *  "data":[
     *      {
     *          "segment_business_id": "业务板块id",
     *          "segment_business_name": "业务板块名称",
     *          "master_business_id": "主业务板块id",
     *          "master_business_name": "主业务板块名称",
     *          "slaver_business_id": "子业务板块id",
     *          "slaver_business_name": "子业务板块名称",
     *          "is_lock":"是否锁定0-未锁定,不可修改1-锁定，可修改",
     *          "user_name": "操作人",
     *          "updated_at": "2019-07-17 15:37:11"
     *      }
     *  ]
     * }
     * @param CustomerSupplier $customerSupplier
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    function show_business(CustomerSupplier $customerSupplier)
    {
        return CustomerSupplierBusinessData::where("customer_supplier_id", $customerSupplier->id)
            ->with(['users', 'segment_business', 'master_business', 'slaver_business'])
            ->get()->map(function ($item) {
                $list = function ($parnt_id) {
                    return \App\Business::query()->where("parent_id", $parnt_id)->get()->map(function ($item) {
                        $return = [];
                        $return['key'] = data_get($item, 'id');
                        $return['value'] = data_get($item, 'name');
                        return $return;
                    });
                };
                /** @var CustomerSupplierBusinessData $item */
                $return = [];
                $return['segment_business_id'] = $item->segment_business_id;
                $return['segment_business_name'] = data_get($item, "segment_business.name");
                $return['segment_business_list'] = $list(0);
                $return['master_business_id'] = $item->master_business_id;
                $return['master_business_name'] = data_get($item, "master_business.name");
                $return['master_business_list'] = $list($return['segment_business_id']);
                $return['slaver_business_id'] = $item->slaver_business_id;
                $return['slaver_business_name'] = data_get($item, "slaver_business.name");
                $return['slaver_business_list'] = $list($return['master_business_id']);
                $return['is_lock'] = $item->is_lock;
                $return['user_name'] = data_get(User::find($item->user_id), "name");
                $return['time'] = (string)$item->updated_at;
                return $return;
            });
    }

    /**
     * 新增、更新、审批02107
     * @jsonParam id int 新增无该参数，修改有该参数
     * @jsonParam name string required 公司/个人全称
     * @jsonParam name_abbreviation string required 公司/个人简称
     * @jsonParam name_code required 公司/个人助记码
     * @jsonParam tax_identification_number string required 公司纳税人识别号
     * @jsonParam contact string 联系人
     * @jsonParam id_card_number string 身份证号
     * @jsonParam tel_area_code int 电话区号
     * @jsonParam tel int 电话
     * @jsonParam mobile int 手机号 Example:13047888888
     * @jsonParam city_id int 国家id Example:1
     * @jsonParam city_id int 城市自增id Example:2
     * @jsonParam address string 地址
     * @jsonParam email string email Example:test@admin.com
     * @jsonParam logistics_role string 物流角色，数字用英文逗号分割 Example:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
     * @jsonParam bank string 开户行信息 Example:[{"name":"中国农业银行","account":"账号1","currency":"CNY"},{"name":"中国农业银行","account":"账号2","currency":"USD"}]
     * @jsonParam is_customer int 0-不是客户 1-是客户 Example:0
     * @jsonParam is_supplier int 0-不是供应商 1-是供应商 Example:1
     * @jsonParam is_invoice int 0-不是结算单位 1-是结算单位 Example:0
     * @jsonParam is_self int 0-不是结算公司 1-是结算公司 Example:1
     * @jsonParam is_lock int 0-取消审批1-审批 Exmaple:0
     * @jsonParam pay_max_time int 最多多少天内付款，值为15,30,45,60 Example:15
     * @jsonParam receive_max_time int 最多多少天内收款，值为15,30,45,60 Example:30
     * @jsonParam credit_max_money int 信控金额
     * @jsonParam credit_max_time int 信控宽限天数
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $messages = [
            'id.is_can_update' => '已经审核，不可修改',
            'id.exists' => '该id不存在',
            'tax_identification_number.*' => '纳税人识别号非法'
        ];
        $rules = [
            'id' => [
                "numeric",
                "is_can_update",
                Rule::exists("customer_suppliers")->whereNull('deleted_at')
            ],
            'name' => [
                "between:1,50",
                Rule::unique('customer_suppliers', 'name')->whereNull("deleted_at")->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->when($request->input('id'), function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where("id", "<>", $request->input('id'));
                    });
                }),
                "required_without:id"
            ],
            'name_abbreviation' => [
                Rule::unique("customer_suppliers", "name_abbreviation")->whereNull('deleted_at')->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->when($request->input('id'), function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where("id", "<>", $request->input('id'));
                    });
                }),
                "between:1,50",
                "required_without:id"
            ],
            'name_code' => [
                Rule::unique("customer_suppliers", "name_code")->whereNull('deleted_at')->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->when($request->input('id'), function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where("id", "<>", $request->input('id'));
                    });
                }),
                "between:1,50",
                "required_without:id"
            ],
            'tax_identification_number' => [
                'between:1,50',
                Rule::unique("customer_suppliers", "tax_identification_number")->whereNull('deleted_at')->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->when($request->input('id'), function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where("id", "<>", $request->input('id'));
                    });
                })],
            'id_card_number' => 'between:1,50',
            'contact' => 'between:1,50',
            'tel_area_code' => 'regex:/^[0-9]{3,4}$/',
            'tel' => 'regex:/^[0-9]{7,11}$/',
            "mobile" => 'numeric',
            "country_id" => "numeric",
            "city_id" => "numeric",
            "address" => "between:1,250",
            "email" => "email",
            "is_customer" => "boolean",
            "is_supplier" => "boolean",
            "is_invoice" => "boolean",
            "is_self" => "boolean",
            "is_lock" => "boolean|required_without:id",
            "logistics_role" => "logistics_role_check",
            "bank" => "banks_check",
            "pay_max_time" => "required|in:15,30,45,60|required_without:id",
            "receive_max_time" => "required|in:15,30,45,60|required_without:id",
            "credit_max_money" => "required|numeric|required_without:id",
            "credit_max_time" => "required|numeric|required_without:id",];
        \Illuminate\Support\Facades\Validator::extend('is_can_update', function ($attribute, $value) {
            if ($attribute !== 'id') {
                return false;
            }
            if (CustomerSupplier::query()->where("id", $value)->where("is_lock", 1)->count()) {
                return false;
            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('logistics_role_check', function ($attribute, $value) {
            if ($attribute != 'logistics_role') {
                return false;
            }
            $var = Config::get('constants.LOGISTICS_ROLE');
            $explode = explode(",", $value);
            foreach ($explode as $v) {
                if (!isset($var[$v])) {
                    return false;
                }
            }
            return true;
        });

        \Illuminate\Support\Facades\Validator::extend('banks_check', function ($attribute, $value) {
            if ($attribute != 'bank') {
                return false;
            }
            if (!is_array($value)) {
                return false;
            }
            foreach ($value as $v) {
                if (!is_array($v)) {
                    return false;
                }
                if (!isset($v['name'])) {
                    return false;
                }
                if (!isset($v['account'])) {
                    return false;
                }
                if (!isset($v['currency'])) {
                    return false;
                }
                if (!isset(Config::get('constants.CURRENCY')[strtoupper($v['currency'])])) {
                    return false;
                }
            }
            $old = count($value);
            $new = collect($value)->unique(function ($item) {
                return $item['name'] . $item['account'] . $item['currency'];
            })->count();
            if ($new != $old) {
                return false;
            }
            return true;
        });
        $this->validate($request, $rules, $messages);
        if ($request->input('id')) {
            $customerSupplier = CustomerSupplier::find($request->input('id'));
        } else {
            $customerSupplier = new CustomerSupplier();
            $customerSupplier->created_user_id = $request->get('login_user_id');
            $customerSupplier->created_user_name = $request->get('login_user_name');
            $customerSupplier->created_time = date("Y-m-d H:i:s");
        }
        $customerSupplier->updated_user_id = $request->get('login_user_id');
        $customerSupplier->updated_user_name = $request->get('login_user_name');
        $customerSupplier->updated_time = date("Y-m-d H:i:s");
        DB::transaction(function () use ($request, $customerSupplier) {
            $request->input('name') && $customerSupplier->name = $request->input('name');
            $request->input('name_abbreviation') && $customerSupplier->name_abbreviation = $request->input('name_abbreviation');
            $request->input('name_code') && $customerSupplier->name_code = $request->input('name_code');
            $request->input('tax_identification_number') && $customerSupplier->tax_identification_number = $request->input('tax_identification_number');
            $request->input('contact') && $customerSupplier->contact = $request->input('contact');
            $request->input('id_card_number') && $customerSupplier->id_card_number = $request->input('id_card_number');
            $request->input('tel_area_code') && $customerSupplier->tel_area_code = $request->input('tel_area_code');
            $request->input('tel') && $customerSupplier->tel = $request->input('tel');
            $request->input('mobile') && $customerSupplier->mobile = $request->input('mobile');
            $request->input('city_id') && $customerSupplier->country_id = $request->input('country_id');
            $request->input('city_id') && $customerSupplier->city_id = $request->input('city_id');
            $request->input('address') && $customerSupplier->address = $request->input('address');
            $request->input('email') && $customerSupplier->email = $request->input('email');
            $request->input('logistics_role') && $customerSupplier->logistics_role = $request->input('logistics_role');
            $request->input('is_customer') !== '' && $customerSupplier->is_customer = $request->input('is_customer');
            $request->input('is_supplier') !== '' && $customerSupplier->is_supplier = $request->input('is_supplier');
            $request->input('is_invoice') !== '' && $customerSupplier->is_invoice = $request->input('is_invoice');
            $request->input('is_self') !== '' && $customerSupplier->is_self = $request->input('is_self');
            $request->input('pay_max_time') && $customerSupplier->pay_max_time = $request->input('pay_max_time');
            $request->input('receive_max_time') && $customerSupplier->receive_max_time = $request->input('receive_max_time');
            $request->input('credit_max_money') && $customerSupplier->credit_max_money = $request->input('credit_max_money');
            $request->input('credit_max_time') && $customerSupplier->credit_max_time = $request->input('credit_max_time');
            $customerSupplier->save();

            $banks = $request->input('bank');
            try {
                collect($banks)->map(function ($item) use ($customerSupplier) {
                    $customerSupplierBankData = new CustomerSupplierBankData();
                    $customerSupplierBankData->name = $item['name'];
                    $customerSupplierBankData->account = $item['account'];
                    $customerSupplierBankData->customer_supplier_id = $customerSupplier->id;
                    $customerSupplierBankData->currency = $item['currency'];
                    $customerSupplierBankData->save();
                });
            } catch (\Exception $e) {
                return ['message' => '银行数据错误', 'code' => 422];
            }
            return true;
        });


        return $customerSupplier;
    }

    /**
     * 批量审批/取消审批02112
     * @bodyParam ids required 客户供应商id,多个用英文逗号隔开 Example:1,2,3,4
     * @bodyParam is_lock required 0:取消审批，1:审批 Example:1
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    function lock(Request $request)
    {
        $messages = [
            'ids.ids_check' => 'id输入错误',
        ];
        $rules = [
            'ids' => 'required|ids_check',
        ];
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            $explode = explode(",", $value);
            if ($attribute !== 'ids') {
                return false;
            }
            if (CustomerSupplier::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        CustomerSupplier::query()->whereIn("id", explode(",", $request->input('ids')))
            ->update([
                'is_lock' => $request->input('is_lock'),
                'lock_user_id' => $request->get('login_user_id'),
                'lock_user_name' => $request->get('login_user_name'),
                'lock_time' => date("Y-m-d H:i:s"),
            ]);
        return [];//$port->refresh();
    }

    /**
     * 删除02108
     * @bodyParam ids required 客户供应商id,多个用英文逗号隔开 Example:1,2,3,4
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    function destroy(Request $request)
    {
        $messages = [
            'ids.ids_check' => 'id输入错误',
            'ids.is_can_destory' => '已经审核通过的不能删除',
        ];
        $rules = [
            'ids' => 'required|ids_check|is_can_destory',
        ];
        \Illuminate\Support\Facades\Validator::extend('is_can_destory', function ($attribute, $value) {
            if ($attribute !== 'ids') {
                return false;
            }
            if (CustomerSupplier::query()->whereIn("id", explode(",", $value))->where("is_lock", 1)->count()) {
                return false;
            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            $explode = explode(",", $value);
            if ($attribute !== 'ids') {
                return false;
            }
            if (CustomerSupplier::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        CustomerSupplier::destroy(explode(",", $request->input('ids')));
        return [];//$port->refresh();
    }

    /**
     * 物流角色列表02109
     *
     * @response {
     *  "1":"委托人",
     *  "2": "船公司",
     *  "3": "订舱公司",
     *  "4": "换单公司",
     *  "5": "货代公司",
     *  "6": "车队",
     *  "7": "保险公司",
     *  "8": "仓储公司",
     *  "9": "铁路公司",
     *  "10": "开证公司",
     *  "11": "提箱公司",
     *  "12": "还箱公司",
     *  "13": "检测公司",
     *  "14": "消毒公司",
     *  "15": "蒸熏公司",
     *  "16": "理货公司",
     *  "17": "装卸公司",
     *  "18": "其他"
     * }
     *
     * @return mixed
     */
    function logisticsRole()
    {
        /** @noinspection PhpUndefinedClassInspection */
        return Config::get('constants.LOGISTICS_ROLE');
    }


}
