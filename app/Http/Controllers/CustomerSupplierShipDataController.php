<?php

namespace App\Http\Controllers;

use App\CustomerSupplier;
use App\CustomerSupplierShipData;
use App\CustomerSupplierShipDataData;
use App\Service\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group 船名029
 */
class CustomerSupplierShipDataController extends Controller
{
    use BusinessService;

    /**
     * 列表02901
     *
     * @bodyParam page int 第几页，默认第一页
     * @bodyParam per_page int 每页记录数，默认是10
     * @bodyParam search string 模糊搜索
     * @bodyParam status int 状态0:禁用，1:启用 Example:1
     * @bodyParam customer_supplier_id int 船公司id
     * @bodyParam customer_supplier_ship_data_id int 船名id
     * @bodyParam segment_business_id int 业务板块id
     * @bodyParam master_business_id int 主业务id
     * @bodyParam slaver_business_id int 子业务id
     * @response {
     * "data":[{
     *  "customer_supplier_id": "船公司id",
     *  "customer_supplier_name": "船公司名称",
     *  "customer_supplier_ship_data_id":"船名id",
     *  "customer_supplier_ship_data_name":"船名",
     *  "customer_supplier_ship_data_status":"船状态0:禁止,1:启用",
     *  "customer_supplier_ship_data_status_alias":"船状态0:禁止,1:启用",
     *  "customer_supplier_ship_data_user_name":"操作人",
     *  "customer_supplier_ship_data_updated_at":"操作时间"
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/shipCompanies?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/shipCompanies?page=5",
     *  "next_page_url": "http://host/api/v1/shipCompanies?page=2",
     *  "path": "http://host/api/v1/shipCompanies",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\AbstractPaginator
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $key = array_search('船公司', \Config::get('constants.LOGISTICS_ROLE'));
        if ($key === false) {
            throw new \Exception('请在constants.php文件内定义船公司常量');
        }
        $list = CustomerSupplierShipData::query()->select([
            "customer_suppliers.id as customer_supplier_id",
            "customer_suppliers.name as customer_supplier_name",
            "customer_supplier_ship_data.id as customer_supplier_ship_data_id",
            "customer_supplier_ship_data.name as customer_supplier_ship_data_name",
            "customer_supplier_ship_data.status as customer_supplier_ship_data_status",
            "customer_supplier_ship_data.user_id as user_id",
            "customer_supplier_ship_data.id as id",
            "customer_supplier_ship_data.status as customer_supplier_ship_data_status",
            "customer_supplier_ship_data.updated_at as customer_supplier_ship_data_updated_at"
        ])->leftJoin("customer_suppliers", "customer_supplier_ship_data.customer_supplier_id", "=", "customer_suppliers.id")
            ->with(['users', 'customer_supplier_ship_data_data'])
            ->when($request->input('search'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("customer_supplier_ship_data.name", "like", "%{$request->input('search')}%")
                        ->orWhere("customer_suppliers.name", "like", "%{$request->input('search')}%");
                });
            })
            ->when($request->has('status') && ($request->input('status') === 0 || $request->input('status') === 1), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where("customer_supplier_ship_data.status", $request->input('status'));
            })
            //船公司
            ->when($request->input('customer_supplier_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('customer_suppliers.id', $request->input('customer_supplier_id'));
            })
            //船名
            ->when($request->input('customer_supplier_ship_data_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('customer_supplier_ship_data.id', $request->input('customer_supplier_ship_data_id'));
            })
            //操作人
            ->when($request->has('user_id') && $request->input('user_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $user_id = $request->input('user_id');
                $q->where("customer_supplier_ship_data.user_id", $user_id);
            })
            //业务板块
            ->when($request->input('segment_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('customer_supplier_ship_data_data', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('segment_business_id', $request->input('segment_business_id'));
                });
            })
            //主业务板块
            ->when($request->input('master_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('customer_supplier_ship_data_data', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('master_business_id', $request->input('master_business_id'));
                });
            })
            //子业务板块
            ->when($request->input('slaver_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('customer_supplier_ship_data_data', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('slaver_business_id', $request->input('slaver_business_id'));
                });
            })
            ->orderBy("customer_suppliers.updated_at", "desc")
            ->orderBy("customer_supplier_ship_data.updated_at", "desc")
            ->paginate($request->get('per_page', 10));
//        return $list;

        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($item) {
            $data = [];
            $data['customer_supplier_id'] = data_get($item, 'customer_supplier_id');
            $data['customer_supplier_name'] = data_get($item, 'customer_supplier_name');
            $data['customer_supplier_ship_data_id'] = data_get($item, 'customer_supplier_ship_data_id');
            $data['customer_supplier_ship_data_name'] = data_get($item, 'customer_supplier_ship_data_name');
            $data['customer_supplier_ship_data_status'] = data_get($item, 'customer_supplier_ship_data_status');
            $data['customer_supplier_ship_data_status_alias'] = data_get($item, 'customer_supplier_ship_data_status') ? "启用" : "禁止";
            $data['customer_supplier_ship_data_user_name'] = data_get($item, 'users.name');
            $data['customer_supplier_ship_data_updated_at'] = data_get($item, 'customer_supplier_ship_data_updated_at');
            return $data;
        }))->toArray();

        $user_list = CustomerSupplierShipData::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        return array_merge($collection, ['user' => $user_list]);
    }

    /**
     * 插入/更新航名02902
     *
     * name和id必须传一个，如果传name表示新增，如果传id表示修改
     * @bodyParam customer_supplier_id int required 船公司id
     * @bodyParam name string 船名称
     * @bodyParam id int 船id
     * @bodyParam status int 船0:禁用,1:启用(默认) Example:1
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        try {
            if ($request->input('customer_supplier_ship_data_id')) {//修改
                $static = CustomerSupplierShipData::find($request->input('customer_supplier_ship_data_id'));
            } else {//新增
                $static = new CustomerSupplierShipData();
            }
            $static->customer_supplier_id = $request->input('customer_supplier_id');
            $static->name = $request->input('customer_supplier_ship_data_name');
            $static->status = $request->input('customer_supplier_ship_data_status');
            $static->user_id = $request->get('login_user_id');
            $static->save();

            $model = $static->where("id", $static->id)->with(['users', 'customer_suppliers'])->first();
            $return = [];
            $return['customer_supplier_id'] = data_get($model, "customer_suppliers.id");
            $return['customer_supplier_name'] = data_get($model, "customer_suppliers.name");
            $return['customer_supplier_ship_data_id'] = data_get($model, "id");
            $return['customer_supplier_ship_data_name'] = data_get($model, "name");
            $return['customer_supplier_ship_data_status'] = data_get($model, "status");
            $return['customer_supplier_ship_data_status_alias'] = data_get($model, "status") ? "启用" : "禁止";
            $return['customer_supplier_ship_data_user_name'] = data_get($model, "users.name");
            $return['customer_supplier_ship_data_updated_at'] = (string)data_get($model, "updated_at");
            return $return;
        } catch (\Exception $e) {
            return ['code' => 422, 'message' => '数据错误'];
        }
    }

    /**
     * 删除船02903
     * @bodyParam ids required 船id,多个id用英文逗号分隔 Example:1,2,3
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Request $request)
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
            if (CustomerSupplierShipData::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        $ids = explode(",", $request->input('ids'));
        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($ids) {
            CustomerSupplierShipData::destroy($ids);
            CustomerSupplierShipDataData::query()->whereIn('customer_supplier_ship_data_id', $ids)->delete();
        });

        return [];
    }

    /**
     * 状态修改02907
     *
     * @bodyParam ids string required 多个id使用英文逗号分割 Example:1,2,3,4
     * @bodyParam status int required 状态 Example:1
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return array
     * @throws
     */
    function status(Request $request)
    {
        $messages = [
            'ids.ids_check' => 'id输入错误',
        ];
        $rules = [
            'ids' => 'required|ids_check',
            'status' => 'required|boolean',
        ];
        \Illuminate\Support\Facades\Validator::extend('ids_check', function ($attribute, $value) {
            $explode = explode(",", $value);
            if ($attribute !== 'ids') {
                return false;
            }
            if (CustomerSupplierShipData::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        CustomerSupplierShipData::query()->whereIn("id", explode(",", $request->input('ids')))->update(['status' => $request->input('status'), 'user_id' => $request->get('login_user_id')]);
        return [];
    }

    /**
     * 插入/更新业务板块关联信息02904
     *
     * @jsonParam customer_supplier_ship_data_id int 船公司船名航次与业务板块类型关系id
     * @jsonParam segment_business_id int 业务板块id
     * @jsonParam master_business_id int 主业务id
     * @jsonParam slaver_business_id int 子业务id
     *
     * @param Request $request
     * @param CustomerSupplierShipData $customerSupplierShipData
     * @return array
     */
    public function update_business(Request $request, CustomerSupplierShipData $customerSupplierShipData)
    {
        $json = $request->getContent();
        $arr = json_decode($json, true);
        if ($arr === null) {
            return ['code' => 422, 'message' => 'json格式错误'];
        }

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request, $customerSupplierShipData) {
            $data = $request->getContent();
            $arr = json_decode($data, true);
            $ids = [];
            foreach ($arr as $k => $v) {
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id']];
            }
            $customerSupplierShipData->businesses()->withTimestamps()->sync($ids);
        });
        return [];
    }

    /**
     * 删除航线下的业务板块02905
     * @bodyParam ids required 船id,多个用英文逗号分割 Example:1,2,3,4
     * @response {
     * }
     * @param Request $request
     * @return array
     */
    public function destroy_business(Request $request)
    {
        $ids = explode(",", $request->input('ids'));
        CustomerSupplierShipDataData::destroy($ids);
        return [];
    }

    /**
     * 业务板块关联信息02906
     * @urlParam customerSupplierShipData required 航次id
     * @response {
     *  "data":[
     *      {
     *          "segment_business_id": "业务板块id",
     *          "segment_business_name": "业务板块名称",
     *          "master_business_id": "主业务板块id",
     *          "master_business_name": "主业务板块名称",
     *          "slaver_business_id": "子业务板块id",
     *          "slaver_business_name": "子业务板块名称",
     *          "user_name": "操作人",
     *          "updated_at": "2019-07-17 15:37:11"
     *      }
     *  ]
     * }
     * /**
     * @param CustomerSupplierShipData $customerSupplierShipData
     * @return CustomerSupplierShipDataData[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    function show_business(CustomerSupplierShipData $customerSupplierShipData)
    {
        return $this->show_business_template($customerSupplierShipData);
    }

    /**
     * 船公司列表02908
     * @response {
     *  "data":[{
     *          "id": "船公司id",
     *          "name": "船公司",
     *          "list":[
     *              {
     *                  "id":1,
     *                  "name":"船名"
     *              }
     *         ]
     * }]
     * }
     * @param CustomerSupplierShipData $customerSupplierShipData
     * @return CustomerSupplierShipDataData[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    function list_result_customer_supplier()
    {
        $key = array_search('船公司', \Config::get('constants.LOGISTICS_ROLE'));
        if ($key === false) {
            throw new \Exception('请在constants.php文件内定义船公司常量');
        }
        return CustomerSupplier::query()->with(['customer_supplier_ship_data'])->whereRaw("find_in_set({$key},logistics_role)")->get()->map(function ($item) {
            /** @var CustomerSupplier $item */
            $return = [];
            $return['id'] = $item->id;
            $return['name'] = $item->name;
            $return['list'] = $item->customer_supplier_ship_data;
            return $return;
        });
    }
}
