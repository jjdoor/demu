<?php

namespace App\Http\Controllers;

/** @noinspection PhpUndefinedClassInspection */

use App\Business;
use App\ClearCompany;
use App\Contract;
use App\ContractData;
use App\CustomerSupplier;
use App\CustomerSupplierBusinessData;
use App\ReviewLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


/**
 * @group 合同审批管理022
 */
class ContractController extends Controller
{
    /**
     * 列表02201
     *
     * @bodyParam page int 第几页，默认为1 Example:1
     * @bodyParam per_page int 每页数，默认为10 Example:10
     * @bodyParam search string 模糊查询
     * @bodyParam status int 办理状态0:未办理，1:已办理 Example:1
     * @bodyParam result int 办理结果-1:退签，1:同意 Example:1
     * @bodyParam process int 办理步骤-0:合同草拟，1:商务会签，2:业务会签，3:领导审批，4:合同归档 Example:1
     * @bodyParam clear_company_id int 结算公司id
     * @bodyParam customer_id int 客户id
     * @bodyParam supplier_id int 供应商id
     * @bodyParam type string 合同类型customer:客户、supplier:供应商 Example:customer
     * @bodyParam sn string 合同编号
     * @bodyParam charge_rule_id 价格协议号
     * @bodyParam is_valid int 合同状态0:无效,1:有效 Example:1
     * @bodyParam segment_business_id 业务板块id
     * @bodyParam master_business_id 主业务板块id
     * @bodyParam process0_user_id 申请人id
     * @bodyParam process0_begin_time 申请开始时间
     * @bodyParam process0_end_time 申请结束时间
     * @bodyParam process1_user_id 商务会签人id
     * @bodyParam process1_begin_time 商务会签开始时间
     * @bodyParam process1_end_time 商务会签结束时间
     * @bodyParam process2_user_id 业务会签人id
     * @bodyParam process2_begin_time 业务开始时间
     * @bodyParam process2_end_time 业务结束时间
     * @bodyParam process3_user_id 审批人id
     * @bodyParam process3_begin_time 审批开始时间
     * @bodyParam process3_end_time 审批结束时间
     * @bodyParam process4_user_id 归档人
     * @bodyParam process4_begin_time 归档开始时间
     * @bodyParam process4_end_time 归档结束时间
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "合同名称",
     *  "process":"合同草拟",
     *  "status":"办理状态",
     *  "result": "办理结果",
     *  "type": "合同类型",
     *  "sn": "合同编号",
     *  "sn_inner": "合同序号",
     *  "clear_company_name": "结算公司",
     *  "part_b_customer_suppliers": "客户",
     *  "part_c_customer_suppliers": "供应商",
     *  "segment_businesses": "业务板块",
     *  "master_businesses": "主业务类型",
     *  "slaver_businesses": "子业务类型",
     *  "slaver_businesses": "价格协议",
     *  "begin_time":"合同生效日",
     *  "end_time": "合同失效日",
     *  "updated_at": "合同状态",
     *  "process0_user_name": "申请人",
     *  "process0_time": "申请时间",
     *  "process1_user_name": "商务会签人",
     *  "process1_time": "商务会签时间",
     *  "process2_user_name": "业务会签人",
     *  "process2_time": "业务会签时间",
     *  "process3_user_name": "审批人",
     *  "process3_time": "审批时间",
     *  "process4_user_name": "归档人",
     *  "process4_time": "归档时间",
     *  "process0_user_list":[
     *      {
     *          "key":"业务申请人key",
     *          "value":"业务申请人value"
     *      }
     *  ],
     *  "process1_user_list":[
     *      {
     *          "key":"商务会签人key",
     *          "value":"商务会签人value"
     *      }
     *  ],
     *  "process2_user_list":[
     *      {
     *          "key":"业务会签人key",
     *          "value":"业务会签人value"
     *      }
     *  ],
     *  "process3_user_list":[
     *      {
     *          "key":"审批人key",
     *          "value":"审批人value"
     *      }
     *  ],
     *  "process4_user_list":[
     *      {
     *          "key":"归档人key",
     *          "value":"归档人value"
     *      }
     *  ],
     *  "clear_company_list":[
     *      {
     *          "key":"结算公司key",
     *          "value":"结算公司value"
     *      }
     *  ],
     *  "customer_list":[
     *      {
     *          "key":"客户key",
     *          "value":"客户value"
     *      }
     *  ],
     *  "supplier_list":[
     *      {
     *          "key":"供应商key",
     *          "value":"供应商value"
     *      }
     *  ],
     *  "sn_list":[
     *      {
     *          "key":"合同编号key",
     *          "value":"合同编号value"
     *      }
     *  ],
     *  "charge_rule_list":[
     *      {
     *          "key":"价格协议号key",
     *          "value":"价格协议号value"
     *      }
     *  ]
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/contracts?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/contracts?page=5",
     *  "next_page_url": "http://host/api/v1/contracts?page=2",
     *  "path": "http://host/api/v1/contracts",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Pagination\AbstractPaginator|mixed
     */
    public function index(Request $request/*,ContractService $contractService*/)
    {
        $contract = Contract::query()->with([
            'is_self_customer_supplier',
            'business_contract' => function ($q) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->with(['segment_business', 'master_business', 'slaver_business']);
            },
            'process0_user',
            'process1_user',
            'process2_user',
            'process3_user',
            'process4_user',
            'contract_customer_suppliers',
            'contract_customer_suppliers_hasMany']);
        //fixme-benjamin
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        /** @noinspection PhpParamsInspection */

        //<editor-fold desc="模糊搜索">
        $contract = $contract->when($request->input('search'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where("name", "like", "%{$request->input('search')}%");
        });
        //</editor-fold>
        //<editor-fold desc="办理结果、办理状态、办理步骤">
        $contract = $contract->when($request->input('process') !== null && $request->input('process') !== '',
            function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereIn('process' . $request->input('process') . '_status', [1, -1])
                    ->when($request->has('status') && $request->input('status') != '', function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $process_status = 'process' . $request->input('process') . "_status";
                        if ($request->input('status') === '0') {
//                $q->whereIn($process_status,[null,0]);
                            $q->where($process_status, 0);
                        } elseif ($request->input('status') === '1') {
                            $q->whereIn($process_status, [-1, 1]);
                        } else {
                            throw new \Exception('status参数异常');
                        }
                    })->when($request->input('result') !== '' && $request->input('result') !== null, function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $process_status = 'process' . $request->input('process') . "_status";
                        $q->where($process_status, $request->input('result'));
                    });
            },
            function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->when($request->input('status') !== '' && $request->input('status') !== null, function ($q) use ($request) {
                    $login_user_process = array_flip(Config::get('constants.REVIEW_ALIAS'))[$request->get('login_role_id')];
                    $process_status = 'process' . $login_user_process . "_status";
                    if ($request->input('status') == 0) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where($process_status, 0);
                    } else {//1
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->whereIn($process_status, [-1, 1]);
                    }
                });
//                    ->when($request->input('status') !== '' && $request->input('status') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
//                    $input = $request->input('status');
//                    $q->whereRaw("process0_status={$input} or process1_status={$input} or process2_status={$input} or process3_status={$input} or process4_status={$input}");
//                });
            });
        //</editor-fold>
        //<editor-fold desc="结算公司">
        $contract = $contract->when($request->input('customer_supplier_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where('customer_supplier_id', $request->input('clear_company_id'));
        });
        //</editor-fold>
        //<editor-fold desc="客户、供应商、合同类型">
        $contract = $contract->when($request->input('type') !== '' && $request->input('type') !== null,
            function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('type', $request->input('type'))
                    ->when($request->has('customer_supplier_id') && $request->input('customer_supplier_id'), function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
//                    $q->whereRaw("find_in_set({$request->input('customer_id_supplier')},customer_supplier_id_string)");
                        $q->whereHas('contract_customer_suppliers_hasMany', function ($q) use ($request) {
                            /** @type \Illuminate\Database\Eloquent\Builder $q */
                            $q->where('customer_supplier_id', $request->input('customer_supplier_id'));
                        });
                    });
            });
        //</editor-fold>
        //<editor-fold desc="合同编号">
        $contract = $contract->when($request->has('sn') && $request->input('sn'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where('sn', $request->input('sn'));
        });
        //</editor-fold>
        //<editor-fold desc="价格协议号">
        $contract = $contract->when($request->has('charge_rule_id') && $request->input('charge_rule_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('business_contract', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('charge_rule_id', $request->input('charge_rule_id'));
            });
        });
        //</editor-fold>
        //<editor-fold desc="业务板块">
        $contract = $contract->when($request->has('segment_business_id') && $request->input('segment_business_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('business_contract', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('segment_business_id', $request->input('segment_business_id'));
            });
        });
        //</editor-fold>
        //<editor-fold desc="主业务板块">
        $contract = $contract->when($request->has('master_business_id') && $request->input('master_business_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('business_contract', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('master_business_id', $request->input('master_business_id'));
            });
        });
        //</editor-fold>
        //<editor-fold desc="子业务板块">
        $contract = $contract->when($request->has('slaver_business_id') && $request->input('slaver_business_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->whereHas('business_contract', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
            });
        });
        //</editor-fold>

        //<editor-fold desc="合同状态，有效无效">
        $contract = $contract->when($request->input('is_valid') !== null && $request->input('is_valid') !== '', function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            if ($request->input('is_valid')) {
                $q->where('begin_time', "<=", date("Y-m-d"))
                    ->where('end_time', ">=", date("Y-m-d"));
            } else {
                $q->where('begin_time', ">", date("Y-m-d"))
                    ->where('end_time', "<", date("Y-m-d"));
            }

        });
        //</editor-fold>
        //<editor-fold desc="审批五个过程的人和时间的搜索">
        foreach ([0, 1, 2, 3, 4] as $key => $value) {
            $contract = $contract->when($request->input("process{$value}_user_id"), function ($q) use ($request, $value) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where("process{$value}_user_id", $request->input("process{$value}_user_id"));
            })->when($request->input("process{$value}_begin_time"), function ($q) use ($request, $value) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where("process{$value}_time", '>=', $request->input("process{$value}_begin_time"));
            })->when($request->input("process{$value}_end_time"), function ($q) use ($request, $value) {
                //申请结束时间
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where("process{$value}_time", '<=', $request->input("process{$value}_end_time"));
            });
        }
        //</editor-fold>
        $list = $contract->orderBy(call_user_func(function () use ($request) {
            return "process" . array_flip(Config::get('constants.REVIEW_ALIAS'))[$request->get('login_role_id')] . "_status";
        }), "asc")
            ->orderBy("contracts.updated_at", "desc")
            ->paginate($request->get('per_page', 10));
        $collection = $list->setCollection($list->getCollection()->map(function ($list) use ($request) {
            /** @var ClearCompany $list */
            $data = [
//                "no"=>$key+1,
                'id' => data_get($list, 'id'),
                'name' => data_get($list, 'name'),
                'process' => call_user_func(function () use ($list, $request) {
                    /** @noinspection PhpUndefinedClassInspection */
                    $process = array_values(Config::get('constants.REVIEW'))[0];
                    foreach ([4, 3, 2, 1, 0] as $k => $v) {
                        if (data_get($list, "process{$v}_status")) {
                            /** @noinspection PhpUndefinedClassInspection */
                            $process = array_values(Config::get('constants.REVIEW'))[$v];
                            break;
                        }
                    }
                    return $process;
                }),
                'status' => call_user_func(function () use ($list, $request) {
                    $login_user_process = array_flip(Config::get('constants.REVIEW_ALIAS'))[$request->get('login_role_id')];
                    $process_status = 'process' . $login_user_process . "_status";
                    $data_get = data_get($list, $process_status);
                    return $data_get > 0 ? "已处理" : "未处理";
                }),
                'result' => call_user_func(function () use ($list, $request) {
                    $login_user_process = array_flip(Config::get('constants.REVIEW_ALIAS'))[$request->get('login_role_id')];
                    $process_status = 'process' . $login_user_process . "_status";
                    $data_get = data_get($list, $process_status);
                    if ($data_get == 1) {
                        return '同意';
                    } elseif ($data_get == 0) {
                        return "-";
                    } elseif ($data_get == -1) {
                        return "退签";
                    } else {
                        throw new \Exception("审批状态异常");
                    }
                }),
                'type' => data_get($list, 'type') == 'customer' ? "客户合同" : "供应商合同",
                'sn' => data_get($list, 'sn'),
                'sn_inner' => data_get($list, 'sn_inner'),
                'clear_company_name' => data_get($list, 'is_self_customer_supplier.name'),
                'customer' => data_get($list, 'type') == 'customer' ? collect(data_get($list, 'contract_customer_suppliers'))->map(function ($item) {
                    /** @var CustomerSupplier $item */
                    return $item->name;
                })->join(",") : "",
                'supplier' => data_get($list, 'type') == 'supplier' ? collect(data_get($list, 'contract_customer_suppliers'))->map(function ($item) {
                    /** @var CustomerSupplier $item */
                    return $item->name;
                })->join(",") : "",
                "segment_business" => collect(data_get($list, 'business_contract'))->map(function ($item) {
                    return data_get($item, "segment_business.name");
                })->join(","),
                "master_business" => collect(data_get($list, 'business_contract'))->map(function ($item) {
                    return data_get($item, "master_business.name");
                })->join(","),
                "slaver_business" => collect(data_get($list, 'business_contract'))->map(function ($item) {
                    return data_get($item, "slaver_business.name");
                })->join(","),
                "charge_rule_name" => collect(data_get($list, 'business_contract'))->map(function ($item) {
                    return data_get($item, "charge_rule_id");
                })->join(","),
                "begin_time" => data_get($list, "begin_time"),
                "end_time" => data_get($list, "end_time"),
                "is_valid" => call_user_func(function () use ($list) {
                    if (data_get($list, "end_time") < date("Y-m-d H:i:s")) {
                        return "过期";
                    } else {
                        return "有效";
                    }
                }),
                "process0_user_name" => data_get($list, 'process0_user.name'),
                "process0_time" => data_get($list, 'process0_time'),
                "process1_user_name" => data_get($list, 'process1_user.name'),
                "process1_time" => data_get($list, 'process1_time'),
                "process2_user_name" => data_get($list, 'process2_user.name'),
                "process2_time" => data_get($list, 'process2_time'),
                "process3_user_name" => data_get($list, 'process3_user.name'),
                "process3_time" => data_get($list, 'process3_time'),
                "process4_user_name" => data_get($list, 'process4_user.name'),
                "process4_time" => data_get($list, 'process4_time'),
                'created_at' => (string)data_get($list, 'created_at', null),
                'updated_at' => (string)data_get($list, 'updated_at', null),
                'is_delete' => call_user_func(function () use ($list) {
                    /** @var Contract $list */
                    if ($list->process0_status == 0 || $list->process0_status == -1) {
                        return 1;
                    }
                    return 0;
                }),];
            return $data;
        }))->toArray();
        $statics = Contract::query()->with(['customer_suppliers', 'is_self_customer_supplier', 'process0_user', 'process1_user', 'process2_user', 'process3_user', 'process4_user'])->get();
        //<editor-fold desc="业务申请人、商务会签人、业务会签人、审批人、归档人">
        $process_user_list = function ($statics, $process) {
            /** @var Collection $statics */
            return $statics->map(function ($item) use ($process) {
                /** @var Contract $item */
                return ['key' => data_get($item, "process{$process}_user.id"), 'value' => data_get($item, "process{$process}_user.name")];
            })->unique("key")->filter(function ($item) {
                return empty($item['key']) ? false : true;
            })->values()->toArray();
        };
        //</editor-fold>
        //<editor-fold desc="结算公司">
        $clear_company_list = $statics->map(function ($item) {
            /** @var Contract $item */
            return ['key' => data_get($item, "is_self_customer_supplier.id"), 'value' => data_get($item, "is_self_customer_supplier.name")];
        })->unique("key")->filter(function ($item) {
            return empty($item['key']) ? false : true;
        })->values()->toArray();
        //</editor-fold>
        //<editor-fold desc="客户">
        $customer_list = $statics->map(function ($item) {
            /** @var Contract $item */
            if ($item->type = 'customer') {
                return data_get($item, "customer_suppliers");
            }
            return null;
        })->collapse()->map(function ($item) {
            return ['key' => data_get($item, 'id'), 'value' => data_get($item, 'name')];
        })->unique("key")->values()->toArray();
        //</editor-fold>
        ////<editor-fold desc="供应商">
        $supplier_list = $statics->map(function ($item) {
            /** @var Contract $item */
            if ($item->type = 'supplier') {
                return data_get($item, "customer_suppliers");
            }
            return null;
        })->collapse()->map(function ($item) {
            return ['key' => data_get($item, 'id'), 'value' => data_get($item, 'name')];
        })->unique("key")->values()->toArray();
        //</editor-fold>
        //<editor-fold desc="合同编号">
        $sn_list = $statics->map(function ($item) {
            /** @var Contract $item */
            return ['key' => $item->sn, 'value' => $item->sn];
        })->values()->toArray();
        //</editor-fold>
        //<editor-fold desc="价格协议号">
        $charge_rule_list = [
            ['key' => 1, 'value' => '价格协议号1'],
            ['key' => 2, 'value' => '价格协议号2'],
            ['key' => 3, 'value' => '价格协议号3'],
        ];
        //</editor-fold>
        return array_merge(
            $collection,
            ['process0_user_list' => $process_user_list($statics, 0)],
            ['process1_user_list' => $process_user_list($statics, 1)],
            ['process2_user_list' => $process_user_list($statics, 2)],
            ['process3_user_list' => $process_user_list($statics, 3)],
            ['process4_user_list' => $process_user_list($statics, 4)],
            ['clear_company_list' => $clear_company_list],
            ['customer_list' => $customer_list],
            ['supplier_list' => $supplier_list],
            ['sn_list' => $sn_list],
            ['charge_rule_list' => $charge_rule_list]
        );
    }

    /**
     * 插入/修改合同草拟02212
     *
     * parent_name和parent_id必须传一个,传parent_name表示新增，传parent_id表示修改
     * name和id必须传一个,传name表示新增，传id表示修改
     * @jsonParam name string required 合同名称
     * @jsonParam sn_alias string 对方合同号
     * @jsonParam type string 合同类型 Example:customer
     * @jsonParam clear_company_id int 结算公司id Example:1
     * @jsonParam attachment string 合同附件 Example:2019-08-23-10-13-36-1392.pdf
     * @jsonParam original_attachment string 合同附件原来名字 Example:上海小微公司合同.pdf
     * @jsonParam attachment_ext string 上传文件扩展名 Example:pdf
     * @jsonParam attachment_size string 合同附件原来名字 Example:12340
     * @jsonParam customer_supplier string 客户供应商,结算单位{"type":"customer表示客户、supplier表示供应商","list":[{"customer_supplier_id":1,"is_invoice":1},{"customer_supplier_id":2,"is_invoice":1}]} Example:{"type":"customer表示客户、supplier表示供应商","list":[{"customer_supplier_id":1,"is_invoice":1},{"customer_supplier_id":2,"is_invoice":1}]}
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_process0(Request $request)
    {
        $messages = [
            'virtual.role_check' => '您没有权限新增，修改',
            'id.is_can_update' => '已经审批，不可修改'
        ];
        $rules = [
            'virtual' => 'role_check',
            'id' => 'numeric|is_can_update',
            'name' => "between:1,50|unique:contracts,name,{$request->input('id')},id|required_without:id",
            'sn_alias' => "between:1,50",
            'type' => "in:customer,supplier|required_without:id",
            //合同附件
            'attachment' => ['regex:/^[0-9\-]{24}\.(pdf|doc|docx)$/', 'required_without:id'],
            'original_attachment' => ['regex:/^.+\.(pdf|doc|docx)$/', 'required_without:id'],
            "attachment_size" => "integer|required_without:id",
            "attachment_ext" => 'in:doc,docx,pdf|required_without:id',
            "clear_company_id" => [
                "required_without:id",
                "integer",
                Rule::exists("customer_suppliers", "id")->where(function ($q) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("is_self", 1)->whereNull('deleted_at');
                }),
            ],
            "customer_supplier" => 'customer_supplier_check|required_without:id',
//            "credit_max_time" => "required|numeric|required_without:id",
        ];
        \Illuminate\Support\Facades\Validator::extend('role_check', function () use ($request) {
            if ($request->get('login_role_id') === Config::get('constants.REVIEW_ALIAS.0', 'default')) {
                return true;
            }
            return false;
        });
        \Illuminate\Support\Facades\Validator::extend('customer_supplier_check', function ($attribute, $value) use ($request) {
            if ($attribute !== 'customer_supplier') {
                return false;
            }
            if (!is_array($value)) {
                return false;
            }
            if (empty($value)) {
                return false;
            }
            if (!isset($value['type'])) {
                return false;
            }
            if (!in_array($value['type'], ['customer', 'supplier'])) {
                return false;
            }
            if (!isset($value['list'])) {
                return false;
            }
            if (!is_array($value['list'])) {
                return false;
            }
            foreach ($value['list'] as $v) {
                if (!isset($v['customer_supplier_id'])) {
                    return false;
                }
                if (!is_int($v['customer_supplier_id'])) {
                    return false;
                }
                if ($v['customer_supplier_id'] <= 0) {
                    return false;
                }
                if (!isset($v['is_invoice'])) {
                    return false;
                }
                if (!in_array($v['is_invoice'], [0, 1])) {
                    return false;
                }
                if ($request->input('type') == 'customer') {
                    if (CustomerSupplier::query()->where("is_customer", 1)->where("id", $v['customer_supplier_id'])->count() == 0) {
                        return false;
                    }
                }
                if ($request->input('type') == 'supplier') {
                    if (CustomerSupplier::query()->where("is_supplier", 1)->where("id", $v['customer_supplier_id'])->count() == 0) {
                        return false;
                    }
                }
                if ($v['is_invoice'] !== 1 && $v['is_invoice'] !== 0) {
                    return false;
                }

            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('is_can_update', function ($attribute, $value) {
            if ($attribute !== 'id') {
                return false;
            }
            //已经审批，不可修改
            if (Contract::query()->where("id", $value)->where("process0_status", 1)->count()) {
                return false;
            }
            return true;
        });
        $this->validate($request, $rules, $messages);

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request) {
            //<editor-fold desc="草拟合同">
//            if ($request->input('id')) {
//                $static = Contract::query()->where("id", $request->input('id'))->first();
//                $static->name = $request->input('name');
//                $static->sn_alias = $request->input('sn_alias');
//                $static->customer_supplier_id = $request->input('clear_company_id');
//                $static->process0_user_id = $request->input('login_user_id');
//                $static->updated_at = date("Y-m-d H:i:s");
//                $static->save();
//            } else {
//                $static = new Contract();
//                $static->name = $request->input('name');
//                $static->sn_alias = $request->input('sn_alias');
//                $static->customer_supplier_id = $request->input('clear_company_id');
//                $static->process0_user_id = $request->input('login_user_id');
//                $static->created_at = date("Y-m-d H:i:s");
//                $static->updated_at = date("Y-m-d H:i:s");
//                $static->save();
//            }
            DB::table('contracts')->updateOrInsert(
                [
                    'id' => $request->input('id'),
                ],
                [
                    'name' => $request->input('name'),
                    'sn_alias' => $request->input('sn_alias'),
                    'type' => $request->input('type'),
                    'customer_supplier_id' => $request->input('clear_company_id'),
                    'process0_user_id' => $request->get('login_user_id'),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'created_at' => date("Y-m-d H:i:s"),
                ]
            );
            //</editor-fold>
            //<editor-fold desc="附件">
            $contract = Contract::query()->where('name', $request->input('name'))->first();
            DB::table('attachments')->updateOrInsert(
                [
                    'foreign_key' => $contract->id,
                    'model' => 'contracts'
                ],
                [
                    'name' => $request->input('attachment'),
                    'model' => 'contracts',
                    'foreign_key' => $contract->id,
                    'original_name' => $request->input('original_attachment'),
                    'ext' => pathinfo($request->input('attachment'))['extension'],
                    'size' => $request->input('attachment_size')
                ]
            );
            //</editor-fold>
            //<editor-fold desc="关联合同和客户供应商">
            collect($request->input('customer_supplier')['list'])->map(function ($item) use ($contract, $request) {
                $contract->customer_suppliers()->attach($item['customer_supplier_id'], [
                    'is_invoice' => $item['is_invoice'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            });

            $arr = $request->input('customer_supplier')['list'];
            $ids = [];
            foreach ($arr as $k => $v) {
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id']];
            }
            $contract->customer_suppliers()->withTimestamps()->sync($ids);


            //</editor-fold>
            //<editor-fold desc="初始化审批流程">
            /** @noinspection PhpUndefinedClassInspection */
            collect(Config::get('constants.REVIEW'))->map(function ($item, $key) use ($request, $contract) {
                ReviewLog::query()->updateOrInsert(
                    [
                        'model' => 'contracts',
                        'foreign_key' => $contract->id,
                        'role_id' => $key,
                        'status' => 0
                    ],
                    [
                        'name' => Config::get('constants.REVIEW')[$request->get('login_role_id')],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
            }
            );
            //</editor-fold>
        });
        return ["id" => Contract::query()->where('name', $request->input('name'))->first()->id];
    }

    /**
     * 详情02203
     * @urlParam contract required 合同自增id
     * @response{
     *  "time_line":{
     *      "font":[
     *          "合同草拟","商务会签","业务会签","领导审批","合同归档"
     *      ],
     *      "time":[
     *          "2019-08-02 10:03:03",null,null,null,"null表示未到该步骤"
     *      ]
     * },
     *  "process0_can_update":"0不可编辑1可编辑",
     *  "process1_can_update":"0不可编辑1可编辑",
     *  "id":"合同自增id",
     *  "name":"合同名称",
     *  "sn_alias":"对方合同编号",
     *  "type":"合同类型customer:客户合同,supplier:供应商合同",
     *  "attachment":{
     *      "id":"附件id",
     *      "name":"附件新名字",
     *      "original_name":"附件原始名字"
     *  },
     *  "customer_suppliers":[
     *      {
     *          "customer_supplier_id":"客户/供应商id",
     *          "is_invoice":"是否结算单位0:否,1:是"
     *      }
     *  ],
     *  "clear_company_id":2,
     *  "process0_user_name":"申请人",
     *  "process0_time":"申请时间",
     *  "sn":"合同编号",
     *  "begin_time":"合同生效日",
     *  "end_time":"合同失效日",
     *  "credit_time_type":"信控基准日1:业务日期,2:开票日期,3:到港日期",
     *  "credit_delay_type":"延迟类型1:延迟月份,2:延后自然日数,3:延后工作日数",
     *  "credit_delay_data":"延后月份:延迟类型为1,1:次月,2:次月月底,3:次次月,4:次次月底,5:次次次月,6:次次次月底;延迟类型为2:表示延后自然日数;延迟类型为3:表示延后工作日数",
     *  "credit_delay_data_data":"延后月份结算日:当是次月、次次月、次次次月才有具体数据天",
     *  "process1_user_name":"商务会签操作人",
     *  "process1_time":"商务会签操作时间",
     *  "is_can_deleted":"0-不可以删除1-可以删除",
     *  "is_can_submit":"0-不可以提交1-可以提交",
     *  "contract_data":[
     *      {
     *          "segment_business_id":"业务板块id",
     *          "segment_business_list":[
     *              {
     *                  "id":5,
     *                  "name":"业务板块"
     *              }
     *          ],
     *          "master_business_id":"主业务类型id",
     *          "master_business_list":[
     *              {
     *                  "id":50,
     *                  "name":"主业务板块"
     *              }
     *          ],
     *          "slaver_business_id":"子业务类型id",
     *          "slaver_business_list":[
     *              {
     *                  "id":500,
     *                  "name":"业务板块"
     *              }
     *          ],
     *          "charge_rule_id":"价格协议id"
     *      }
     *  ],
     *  "review_logs":[{
     *      "process_name":"步骤名称",
     *      "depart_name":"部门名称",
     *      "process_users_name":"办理人",
     *      "process_status":"已办理/未办理",
     *      "process_result":"申请/同意/归档",
     *      "process_suggestion":"办理意见",
     *      "process_time":"办理时间"
     * }]
     * }
     * @param Request $request
     * @param Contract $contract
     * @return array
     */
    public function show(Request $request, Contract $contract)
    {
        //<editor-fold desc="合同详情">
        $contract = Contract::query()->with(['attachments', 'business_contract' => function ($q) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->with(['segment_business', 'master_business', 'slaver_business'/*,'charge_rules'*/]);
        }, 'contract_customer_suppliers',
            'review_logs' => function ($q) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->with(['users', 'roles']);
            }])
            ->where('id', $contract->id)->first();
//        return $contract;
        //</editor-fold>
        //<editor-fold desc="输出数据重构">
        $return = function ($contract, $request) {
            /** @var Contract $contract */
            $return = [];
            $return['is_can_submit'] = call_user_func(function () use ($contract, $request) {
                $process_role_id = null;
                foreach (Config::get("constants.REVIEW_ALIAS") as $process => $role_id) {
                    $temp = "process" . $process . "_status";
                    if ($contract->$temp == 0 || $contract->$temp == -1) {
                        $process_role_id = $role_id;
                        break;
                    }
                }
                if ($process_role_id == $request->get('login_role_id')) {
                    return 1;
                }
                return 0;
            });
            /** @noinspection PhpUndefinedClassInspection */
            $return['time_line'] = [
                'font' => array_values(Config::get('constants.REVIEW')),
                'time' => [$contract->process0_time, $contract->process1_time, $contract->process2_time, $contract->process3_time, $contract->process4_time],
                'status' => [$contract->process0_status, $contract->process1_status, $contract->process2_status, $contract->process3_status, $contract->process4_status]
            ];
            //<editor-fold desc="业务部">
            $return['process0_can_update'] = call_user_func(function ($contract) use ($request) {
                /** @var Contract $contract */
                /** @var Request $request */
                if (($contract->process0_status == 0 || $contract->process0_status == -1) &&
                    Config::get("constants.REVIEW_ALIAS.0") === $request->get('login_role_id')
                ) {
                    return 1;
                }
                return 0;
            }, $contract);
            //</editor-fold>
            $return['process1_can_update'] = call_user_func(function ($contract) use ($request) {
                /** @var Contract $contract */
                /** @var Request $request */
                if ((($contract->process1_status == 0 || $contract->process1_status == -1) && $contract->process0_status == 1) &&
                    (Config::get("constants.REVIEW_ALIAS.1") === $request->get('login_role_id'))
                ) {
                    return 1;
                }
                return 0;
            }, $contract);
            $return['id'] = $contract->id;
            $return['name'] = $contract->name;
            $return['sn_alias'] = $contract->sn_alias;
            $return['type'] = $contract->type;
            $return['attachment'] = data_get($contract, 'attachments.name');
            $return['customer_suppliers'] = collect(data_get($contract, 'contract_customer_suppliers'))->map(function ($item) {
                /** @var CustomerSupplier $item */
                return ['customer_supplier_id' => $item->id, 'is_invoice' => $item->is_invoice];
            });
            $return['clear_company_id'] = $contract->customer_supplier_id;
            $return['process0_user_name'] = data_get(User::find($contract->process0_user_id), 'name');
            $return['process0_time'] = $contract->process0_time;
            $return['sn'] = $contract->sn;
            $return['begin_time'] = $contract->begin_time;
            $return['end_time'] = $contract->end_time;
            $return['credit_delay_type'] = $contract->credit_delay_type;
            $return['credit_time_type'] = $contract->credit_time_type;
            $return['credit_delay_data'] = $contract->credit_delay_data;
            $return['credit_delay_data_data'] = $contract->credit_delay_data_data;
            $return['process1_user_name'] = data_get(User::find($contract->process1_user_id), 'name');
            $return['process1_time'] = $contract->process1_time;
            $return['contract_data'] = collect($contract->business_contract)->map(function ($item) {
                $data = [];
                /** @var ContractData $item */
                $data['segment_business_id'] = $item->segment_business_id;
                $data['segment_business_list'] = Business::query()->where("parent_id", 0)->get();
                $data['master_business_id'] = $item->master_business_id;
                $data['master_business_list'] = Business::query()->where("parent_id", $item->segment_business_id)->get();
                $data['slaver_business_id'] = $item->slaver_business_id;
                $data['slaver_business_list'] = Business::query()->where("parent_id", $item->master_business_id)->get();
                $data['charge_rule_id'] = $item->charge_rule_id;
                return $data;
            });
            $return['review_logs'] = collect($contract->review_logs)->map(function ($item) {
                /** @var ReviewLog $item */
                $data = [];
//            $data['name'] = data_get($item,"name");
                $data['process_users_name'] = data_get($item, "users.name");
                $data['depart_name'] = data_get($item, "roles.name");
//            $data['roles_name'] = data_get($item,"roles.name");
                /** @noinspection PhpUndefinedClassInspection */
                $data['process_name'] = data_get(Config::get("constants.REVIEW"), [data_get($item, 'roles.id')]);
                $data['process_status'] = data_get($item, "status") == 0 ? "未办理" : "已办理";
                if (data_get($item, "status") == -1) {
                    $data['process_result'] = "退回";
                } elseif (data_get($item, 'status') == 0) {
                    $data['process_result'] = '未办理';
                } else {
                    $process_location = $this->_process_location(data_get($item, 'role_id'));
                    if ($process_location == 0) {
                        $data['process_result'] = "提交";
                    } elseif ($process_location == 4) {
                        $data['process_result'] = '归档';
                    } else {
                        $data['process_result'] = '同意';
                    }
                }
                $data['process_suggestion'] = data_get($item, "suggestion");
                $data['process_time'] = (string)data_get($item, "updated_at");
                return $data;
            });

            $return['attachment'] = data_get($contract, 'attachments');
            $return['is_can_deleted'] = call_user_func(function () use ($contract) {
                /** @var Contract $contract */
                if ($contract->process0_status == 0 || $contract->process0_status == -1) {
                    return 1;
                }
                return 0;
            });
            return $return;
        };
        //</editor-fold>
        return $return($contract, $request);
    }

    /**
     * 角色所处的流程位置
     * @param $role_id
     * @return false|int|string
     */
    private function _process_location($role_id)
    {
        #当前角色，是否可提交审批
        /** @noinspection PhpUndefinedClassInspection */
        $role_ids = array_keys(Config::get('constants.REVIEW'));
        //登录角色所处的审批流程位置
        $process_location = array_search($role_id, $role_ids);
        return $process_location;
    }

    /**
     * 插入商务会签02211
     *
     * @jsonParam id int required 合同id
     * @jsonParam sn string required 合同编号
     * @jsonParam begin_time string required 合同生效开始日 Example:2020-01-01
     * @jsonParam end_time string required 合同生效结束日 Example:2020-12-12
     * @jsonParam credit_time_type int required 信控基准日1:业务日期,2:开票日期,3:到港日期 Example:1
     * @jsonParam credit_time_type int required 信控基准日1:业务日期,2:开票日期,3:到港日期 Example:1
     * @jsonParam credit_delay_type int 延迟类型1:延迟月份,2:延后自然日数,3:延后工作日数 Example:1
     * @jsonParam credit_delay_data int 延后月份:延迟类型为1,1:次月,2:次月月底,3:次次月,4:次次月底,5:次次次月,6:次次次月底;延迟类型为2:表示延后自然日数;延迟类型为3:表示延后工作日数
     * @jsonParam credit_delay_data_data int 延后月份结算日:当是次月、次次月、次次次月才有具体数据天
     * @jsonParam business string required 业务[{"segment_business_id":1,"master_business_id":2,"slaver_business_id":3,"charge_rule_id":1}] Example:[{"segment_business_id":1,"master_business_id":2,"slaver_business_id":3,"charge_rule_id":1}]
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_process1(Request $request)
    {
        $messages = [
            'id.status_check' => '不可修改',
        ];
        $rules = [
            'id' => 'required|integer|status_check|role_check',
            'sn' => 'between:1,50|required',
            'begin_time' => 'date|required',
            'end_time' => 'date|after:begin_time|required',
            'credit_time_type' => 'in:1,2,3|required',
            'credit_delay_type' => 'in:1,2,3',
            'credit_delay_data' => [
                'regex:/^[1-9]+[0-9]*$/',
            ],
            'credit_delay_data_data' => [
                'regex:/^[1-9]+[0-9]*$/',
                Rule::requiredIf(function () use ($request) {
                    if ($request->input('credit_delay_type') == 1) {
                        if (in_array($request->input('credit_delay_data'), [1, 3, 5])) {
                            return true;
                        }
                    }
                    return false;
                }),
            ],
            'business' => "business_check"
        ];
        \Illuminate\Support\Facades\Validator::extend('status_check', function ($attribute, $value) {
            if ($attribute !== 'id') {
                return false;
            }
            $static = Contract::query()->where("id", $value)->first();
            //<editor-fold desc="合同草拟保存状态，不可进入商务会签编写">
            if ($static->process0_status == -1 || $static->process0_status == 0) {
                return false;
            }
            //</editor-fold>

            //<editor-fold desc="商务会签已经审批,不可对商会会签编写">
            if ($static->process1 == 1 && $static->process1_status == -1) {
                return false;
            }
            //</editor-fold>

            return true;
        });
        $self = $this;
        \Illuminate\Support\Facades\Validator::extend('role_check', function ($attribute) use ($request, $self) {
            if ($attribute !== 'id') {
                return false;
            }
            //<editor-fold desc="登录角色，是否有保存权限">
            if ($self->_process_location($request->get('login_role_id')) != 1) {
                return false;
            }
            //</editor-fold>
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('business_check', function ($attribute, $value) {
            if ($attribute !== 'business') {
                return false;
            }
            $businesses = $value;
            foreach ($businesses as $business) {
                if (!isset($business['segment_business_id'], $business['master_business_id'], $business['slaver_business_id'])) {
                    return false;
                }
                if (!preg_match("/^[1-9][0-9]*$/", $business['segment_business_id'])) {
                    return false;
                }
                if (!preg_match("/^[1-9][0-9]*$/", $business['master_business_id'])) {
                    return false;
                }
                if (!preg_match("/^[1-9][0-9]*$/", $business['slaver_business_id'])) {
                    return false;
                }
                $static = Business::query()->where("id", $business['slaver_business_id'])->first();
                if (empty($static)) {
                    return false;
                }
                if ($static->parent_id != $business['master_business_id']) {
                    return false;
                }
                $static1 = Business::query()->where("id", $business['master_business_id'])->first();
                if (empty($static1)) {
                    return false;
                }
                if ($static1->parent_id != $business['segment_business_id']) {
                    return false;
                }
                $static2 = Business::query()->where("id", $business['segment_business_id'])->first();
                if (empty($static2)) {
                    return false;
                }
                if ($static2->parent_id != 0) {
                    return false;
                }
                if (isset($business['charge_rule_id']) && !empty($business['charge_rule_id'])) {
                    if (!preg_match("/^[1-9][0-9]*$/", $business['charge_rule_id'])) {
                        return false;
                    }
                    //fixme-benjamin 需要数据完整性判断
//                            if (ChargeRule::query()->where("id", $businesses['charge_rule_id'])->count() == 0) {
//                                return false;
//                            }
                }
            }
            return true;
        });
        $this->validate($request, $rules, $messages);

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request) {
            //<editor-fold desc="保存商务会签">
            $contract = Contract::query()->where("id", $request->input('id'))->first();
            $contract->sn = $request->input('sn');
            $contract->begin_time = $request->input('begin_time');
            $contract->end_time = $request->input('end_time');
            $contract->credit_time_type = $request->input('credit_time_type');
            $request->input('credit_delay_type') && $contract->credit_delay_type = $request->input('credit_delay_type');
            $request->input('credit_delay_data') && $contract->credit_delay_data = $request->input('credit_delay_data');
            $request->input('credit_delay_data_data') && $contract->credit_delay_data_data = $request->input('credit_delay_data_data');
            $contract->process1_user_id = $request->get('login_user_id');
            $contract->process1_time = date('Y-m-d H:i:s');
            $contract->save();
            //</editor-fold>

            //<editor-fold desc="保存业务板块">
            //fixme-benjamin
            collect($request->input('business'))->map(function ($item) use ($contract, $request) {
                $contract_data = new ContractData();
                $contract_data->contract_id = $contract->id;
                $contract_data->segment_business_id = $item['segment_business_id'];
                $contract_data->master_business_id = $item['master_business_id'];
                $contract_data->slaver_business_id = $item['slaver_business_id'];
                $contract_data->charge_rule_id = 1;//fixme-benjamin
                $contract_data->save();
            });

            $arr = $request->input('business');
            $ids = [];
            foreach ($arr as $k => $v) {
                //fixme-benjamin 修复charge_rule_id
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'charge_rule_id' => 1, 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id']];
            }
            $contract->businesses()->withTimestamps()->sync($ids);

            //</editor-fold>

            //<editor-fold desc="业务板块也保存到客户供应商关联表">
            collect(data_get(Contract::query()->with(['contract_customer_suppliers'])->where("id", $contract->id)->first(), "contract_customer_suppliers"))->map(function ($item) {
                return $item['id'];
            })->map(function ($item) use ($request) {
                collect($request->input('segment_business_id'))->map(function ($v, $k) use ($item, $request) {
                    CustomerSupplierBusinessData::updateOrInsert(
                        [
                            'customer_supplier_id' => $item,
                            'segment_business_id' => $v,
                            'master_business_id' => $request->input('master_business_id')[$k],
                            'slaver_business_id' => $request->input('slaver_business_id')[$k],
                            'charge_rule_id' => $request->input('charge_rule_id')[$k],
                        ],
                        [
                            'is_lock' => 1
                        ]
                    );
                });
            });
            //</editor-fold>
        });
        return [];
    }

    /**
     * 删除02205
     * @bodyParam ids required 合同id Example:1,2,3
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Request $request)
    {
        $messages = [
            'ids.check' => '不可删除',
        ];
        $rules = [
            'ids' => 'required|ids_check|role_check',
        ];
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            if ($attribute !== 'ids') {
                return false;
            }
            $ids = explode(",", $value);
            if (Contract::query()->whereIn('id', $ids)->count() != count($ids)) {
                return false;
            }
            return true;
        });

        \Illuminate\Support\Facades\Validator::extend('role_check', function ($attribute, $value) {
            if ($attribute !== 'ids') {
                return false;
            }

            $ids = explode(",", $value);
            $contracts = Contract::query()->whereIn('id', $ids)->get();
            foreach ($contracts as $contract) {
                if ($contract->process0_status == 1) {
                    return false;
                }
            }
            return true;
        });
        $this->validate($request, $rules, $messages);

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request) {
            $ids = explode(",", $request->input('ids'));
            Contract::destroy($ids);
//            RoleReview::query()->where("model", "contract")->where('foreign_key', $request->input('id'))->delete();
        });
        /** @noinspection PhpUndefinedClassInspection */
//        $builder = Attachment::query()->where("model", "contracts")->where("foreign_key", $request->input('id'));
//        $static = $builder->first();
//        AliyuncsOssService::deleteObject($static->name);
//        $builder->delete();
        return [];
    }

    function submit_m(Request $request)
    {

    }

    /**
     * 提交02210
     *
     * @bodyParam id int required 合同id
     * @bodyParam status int required 审核 -1-审核不通过、1-审核通过 Example:1
     * @bodyParam sn_inner string 内部管理合同该编号(只有最后一步归档才需要)
     * @bodyParam suggestion 建议
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    function submit(Request $request)
    {
        //<editor-fold desc="初始化变量">
//        $role_process = $process_location = $this->_process_location($request->get('login_role_id'));

        $contract = Contract::find($request->input('id'));
        //</editor-fold>

        //<editor-fold desc="审批权限校验">
        $messages = [
            "id.is_can_submit" => '您没有提交权限',
            "id.is_can_return" => '您没有退回权限',
        ];
        $rules = [
            "id" => [
                'required',
                Rule::exists("contracts")->whereNull('deleted_at'),
                'is_can_submit',
                'is_can_return',
                'is_can_review',
            ],
            "status" => 'required|in:-1,1',
            "sn_inner" => [
                Rule::requiredIf(function () use ($request) {
                    if ($this->_process_location($request->get('login_role_id')) == 4) {
                        return true;
                    }
                    return false;

                }),
                "sn_inner_check"
            ],
            'suggestion' => 'between:1,250'
        ];

        \Illuminate\Support\Facades\Validator::extend('is_can_submit', function ($attribute) use ($request, $contract) {
            if ($attribute !== 'id') {
                return false;
            }

            $c = function ($contract) {
                $process_arr = array_keys(Config::get('constants.REVIEW_ALIAS'));
                foreach ($process_arr as $k) {
                    $process = "process{$k}_status";
                    if ($contract->$process == -1 || $contract->$process == 0) {
                        return $k;
                    }
                }
                return null;
            };

            $role_process = array_search($request->get('login_role_id'), Config::get('constants.REVIEW_ALIAS'));
            if ($role_process !== $c($contract)) {
                return false;
            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('sn_inner_check', function ($attribute, $value) {
            if ($attribute !== 'sn_inner') {
                return false;
            }
            if (Contract::query()->where('sn_inner', $value)->count()) {
                return false;
            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('is_can_return', function ($attribute) use ($request) {
            if ($attribute !== 'id') {
                return false;
            }
            $role_process = array_search($request->get('login_role_id'), Config::get('constants.REVIEW_ALIAS'));
            if ($role_process == 4 && $request->input('status') == -1) {
                return false;
            }
            return true;
        });
        \Illuminate\Support\Facades\Validator::extend('is_can_review', function ($attribute) use ($request, $contract) {
            if ($attribute !== 'id') {
                return false;
            }
            $process_location = array_search($request->get('login_role_id'), Config::get('constants.REVIEW_ALIAS'));
            //当前登录用户角色在审批流程中的位置
//            $status = "process{$process_location}_status";
            if ($process_location == 0) {
                if ($contract->process0_status == 0 || $contract->process0_status == -1) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($process_location == 1) {
                if (($contract->process1_status == 0 || $contract->process1_status == -1) && $contract->process0_status == 1) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($process_location == 2) {
                if (($contract->process2_status == 0 || $contract->process2_status == -1) && $contract->process1_status == 1) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($process_location == 3) {
                if (($contract->process3_status == 0 || $contract->process3_status == -1) && $contract->process2_status == 1) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($process_location == 4) {
                if (($contract->process4_status == 0 || $contract->process4_status == -1) && $contract->process3_status == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
        $this->validate($request, $rules, $messages);
        //</editor-fold>
        //<editor-fold desc="审批通过">
        $process_location = array_search($request->get('login_role_id'), Config::get('constants.REVIEW_ALIAS'));
        $process_status = "process{$process_location}_status";
        $process_user_id = "process{$process_location}_user_id";
        $process_time = "process{$process_location}_time";
        $contract->$process_status = $request->input('status');
        $contract->$process_user_id = $request->get('login_user_id');
        $contract->$process_time = date('Y-m-d H:i:s');
        if ($process_location == 4) {
            $contract->sn_inner = $request->input('sn_inner');
        }
        //</editor-fold>
        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request, $contract, $process_location) {
            $contract->save();
            //<editor-fold desc="审批不通过">
            if ($request->input('status') == -1) {
                $contract->process0_status = 0;
                $contract->process0_time = null;
                $contract->process1_status = 0;
                $contract->process1_user_id = null;
                $contract->process1_time = null;
                $contract->process2_status = 0;
                $contract->process2_user_id = null;
                $contract->process2_time = null;
                $contract->process3_status = 0;
                $contract->process3_user_id = null;
                $contract->process3_time = null;
                $contract->process4_status = 0;
                $contract->process4_user_id = null;
                $contract->process4_time = null;

                $process_user = "process" . $process_location . "_user_id";
                $process_cur = "process" . $process_location . "_status";
                $process_time = "process" . $process_location . "_time";
                $contract->$process_user = $request->get('login_user_id');
                $contract->$process_cur = -1;
                $contract->$process_time = date("Y-m-d H:i:s");

                $contract->save();
            }
            //</editor-fold>
            //<editor-fold desc="写入审批记录">
            $this->_review($request, $contract, $process_location);
            //</editor-fold>
            return [];

        });

        return [];
    }

    private function _review(Request $request, Contract $contract, $process_location)
    {
        /** @noinspection PhpUndefinedClassInspection */
        $role_ids = array_keys(Config::get('constants.REVIEW'));
        $role_id = $role_ids[$process_location];
        /** @noinspection PhpUndefinedClassInspection */
        $data = ReviewLog::query()->updateOrInsert(
            [
                'model' => 'contracts',
                'foreign_key' => $contract->id,
                'role_id' => $role_id,
                'status' => 0
            ],
            [
                'user_id' => $request->get('login_user_id'),
                'name' => Config::get('constants.REVIEW')[$request->get('login_role_id')],
                'status' => $request->input('status'),
                'suggestion' => $request->input('suggestion'),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        return $data;
    }

    //角色所处流程位置

    function unsubmit(Request $request)
    {

    }

    /**
     * 审批日志02211
     * @response {
     *   [
     *      "id":7,
     *      "process_name":"步骤",
     *      "process_users_name":"办理人",
     *      "depart_name":"部门名称",
     *      "process_status":"办理状态",
     *      "process_result":"办理结果",
     *      "process_suggestion":"办理意见",
     *      "process_time":"办理时间"
     *   ]
     * }
     * @param Contract $contract
     * @return ReviewLog[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    function review_list(Contract $contract)
    {
        $r = ReviewLog::with(['users', 'roles'])->where("model", "contracts")->where("foreign_key", $contract->id)->orderBy("id", "asc")->get()->map(function ($item) {
            $data = [];
//            $data['name'] = data_get($item,"name");
            $data['process_users_name'] = data_get($item, "users.name");
            $data['depart_name'] = data_get($item, "roles.name");
//            $data['roles_name'] = data_get($item,"roles.name");
            /** @noinspection PhpUndefinedClassInspection */
            $data['process_name'] = Config::get("constants.REVIEW")[data_get($item, 'roles.id')];
            $data['process_status'] = data_get($item, "status") == 0 ? "未办理" : "已办理";
            if (data_get($item, "status") == -1) {
                $data['process_result'] = "退回";
            } elseif (data_get($item, 'status') == 0) {
                $data['process_result'] = '未办理';
            } else {
                $process_location = $this->_process_location(data_get($item, 'role_id'));
                if ($process_location == 0) {
                    $data['process_result'] = "提交";
                } elseif ($process_location == 4) {
                    $data['process_result'] = '归档';
                } else {
                    $data['process_result'] = '同意';
                }
            }
            $data['process_suggestion'] = data_get($item, "suggestion");
            $data['process_time'] = (string)data_get($item, "updated_at");
            return $data;
        });
        return $r;
    }

    /**
     * 模糊搜索02213
     * 客户、供应商、合同编号、价格协议号
     * @queryParam keyword string required 搜索关键字
     * @queryParam type string required 搜索关键字类型(customer:客户、supplier:供应商、contract:合同编号、charge_rule:价格协议号) Example:customer
     * @response {[
     *    "id":"id",
     *    "name":"名称"
     * ]}
     * @param Request $request
     * @return Contract[]|CustomerSupplier[]|array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     * @throws \Exception
     */
    function search(Request $request)
    {
        $input = $request->input('type');
        if ($input == 'customer' || $input == 'supplier') {
            $list_results = CustomerSupplier::query()->select(["id", "name"])
                ->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("name", "like", "%{$request->input('keyword')}%")
                        ->orWhere('name_abbreviation', "like", "%{$request->input('keyword')}%")
                        ->orWhere('name_code', "like", "%{$request->input('keyword')}%")
                        ->orWhere('tax_identification_number', "like", "%{$request->input('keyword')}%");
                })->where($input == 'customer' ? "is_customer" : "is_supplier", 1)
                ->get();
            return $list_results;
        } elseif ($input == 'contract') {
            return Contract::query()->select(["id", "name"])->where("sn", "like", "%{$request->input("keyword")}%")->get();
        } elseif ($input == "charge_rule") {
//            ChargeRule::query()->select(["id","name"])->where("")
            //benjamin-fixme
            return [
                ["id" => 1, "name" => 'charge_rule1'],
                ["id" => 2, "name" => 'charge_rule2'],
            ];
        } else {
            throw new \Exception("搜索错误");
        }
    }

    /**
     * 当前需要审批的流程
     * @param Contract $contract
     * @return mixed|null
     */
//    private function _current_process(Contract $contract)
//    {
//        $process_arr = [0, 1, 2, 3, 4, 5];
//        foreach ($process_arr as $k) {
//            $process = "process{$k}_status";
//            if ($contract->$process == -1 || $contract->$process == 0) {
//                return $k;
//            }
//        }
//        return null;
//    }
}
