<?php

namespace App\Http\Controllers;

use App\Business;
use App\Port;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group 港口004
 */
class PortController extends Controller
{
    /**
     * 列表00401
     *
     * @bodyParam page int 第几页，默认第一页 Example:1
     * @bodyParam per_page int 每页记录数，默认是10 Example:10
     * @bodyParam search string 模糊搜索 Example:i
     * @bodyParam status int 状态0:禁用，1:启用 Example:1
     * @bodyParam id int 港口id
     * @bodyParam country string 国家 Example:中国
     * @bodyParam user_id int 操作人 Example:1
     * @bodyParam segment_business_id int 主业务板块
     * @bodyParam master_business_id int 主业务类型
     * @bodyParam slaver_business_id int 子业务类型
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "港口名称",
     *  "status":"0-禁止1-启用",
     *  "user_name":"用户名",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
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
     * @param Request $request
     * @return array
     * @throws
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'between:1,50',//|required
            'status' => 'boolean',
            'user_id' => 'integer',
            'id' => 'integer',
            'segment_business_id' => 'integer',
            'master_business_id' => 'integer',
            'slaver_business_id' => 'integer',
        ]);

        $list = Port::query()->with(['users', 'segment_businesses', 'master_businesses', 'slaver_businesses'])
            ->when($request->input('search') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('name', 'like', "%{$request->input('search')}%")
                        ->orWhere('name_code', 'like', "%{$request->input('search')}%")
                        ->orWhere('country', 'like', "%{$request->input('search')}%");
                });
            })->when($request->input('status') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('status', $request->input('status'));
            })->when($request->input('id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('id', $request->input('id'));
            })->when($request->input('country') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('country', $request->input('country'));
            })->when($request->input('user_id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('user_id', $request->input('user_id'));
            })->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('segment_businesses', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("segment_business_id", $request->input('segment_business_id'));
                });
            })->when($request->input('master_business_id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('master_businesses', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("master_business_id", $request->input('master_business_id'));
                });
            })->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('slaver_businesses', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where("slaver_business_id", $request->input('slaver_business_id'));
                });
            })
            ->orderBy("updated_at", "desc")->paginate($request->get('per_page', 10));
//        return $list;
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            $data = [
                'id' => data_get($list, 'id'),
                'name' => data_get($list, 'name'),
                'name_code' => data_get($list, 'name_code'),
                'country' => data_get($list, 'country'),
                'status' => data_get($list, 'status'),
                'status_alias' => data_get($list, 'status') ? "启用" : "禁用",
                'user_name' => data_get($list, 'users.name'),
                'created_at' => (string)data_get($list, 'created_at', null),
                'updated_at' => (string)data_get($list, 'updated_at', null),

            ];
            return $data;
        }))->toArray();

        $user_list = Port::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        $country = Port::query()->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'country');
            $return['value'] = data_get($item, 'country');
            return $return;
        })->unique('key')->values();

        $name = Port::query()->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'id');
            $return['value'] = data_get($item, 'name') . "(" . data_get($item, 'name_code') . ")";
            return $return;
        })->unique('key')->values();

        return array_merge($collection, ['user' => $user_list], ['country' => $country], ['name' => $name]);
    }

    /**
     * 港口、港口助记码模糊搜索00408
     *
     * @bodyParam search string required 模糊搜索关键字
     * @response {
     *  "id": 4,
     *  "name": "港口名称",
     *  "name_code": "港口助记码",
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return Port[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     * @throws
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'search' => 'between:1,50',//|required
        ]);

        return Port::query()->select(["id", "name", "name_code"])->where("name", "like", "%{$request->input('search')}%")
            ->orWhere("name_code", "like", "%{$request->input('search')}%")
            ->get();
    }

    /**
     * 状态修改00402
     *
     * @bodyParam ids string required 多个id使用英文逗号分割 Example:1,2,3,4
     * @bodyParam status int required 状态 Example:1
     * @response {
     *  "id": 4,
     *  "name": "港口名称",
     *  "name_code": "港口助记码",
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
            if (Port::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        Port::query()->whereIn("id", explode(",", $request->input('ids')))->update(['status' => $request->input('status')]);
        return [];
    }

    /**
     * 保存00403
     *
     * @bodyParam name string required 港口名称
     * @bodyParam name_code string required 港口助记码
     * @bodyParam country string required 国家
     * @bodyParam status int required 状态 Example:1
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return Port|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'between:1,50|required',//
            'name_code' => 'between:1,50|required',//
            'country' => 'between:1,50|required',//
            'status' => 'boolean|required',
        ]);
        $port = new Port();
        $port->name = $request->input('name');
        $port->name_code = $request->input('name_code');
        $port->country = $request->input('country');
        $port->status = $request->input('status');
        $port->user_id = $request->get('login_user_id');
        $port->save();
        return $port->where("id", $port->id)->with(['users'])->first();
    }

    /**
     * 港口业务板块插入更新00404
     *
     * @urlParam id int required 港口名称id
     * @jsonParam segment_business_id int required 业务板块id
     * @jsonParam master_business_id int required 主业务id
     * @jsonParam slaver_business_id int required 子业务id
     * @response {
     *  "id": 4,
     *  "name": "港口名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "status":"0-禁止1-启用"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @param Port $port
     * @return array
     */
    public function updateOrInsert(Request $request, Port $port)
    {
        $json = $request->getContent();
        $arr = json_decode($json, true);
        if ($arr === null) {
            return ['code' => 422, 'message' => 'json格式错误'];
        }

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request, $port) {
            $data = $request->getContent();
            $arr = json_decode($data, true);
            $ids = [];
            foreach ($arr as $k => $v) {
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id']];
            }
            $port->slaver_businesses()->withTimestamps()->sync($ids);
        });
        return [];
    }

    /**
     * 详情00405
     *
     * @queryParam port 港口id
     * @response {
     *  "id": 4,
     *  "name": "港口名称",
     *  "status":0,
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Port $port
     * @return Port
     */
//    public function show(Port $port)
//    {
//        return $port;
//    }
    /**
     * 更新00406
     * @urlParam port required 港口id
     * @bodyParam name  港口名称
     * @bodyParam name_code  港口名称助记码
     * @bodyParam country  国家
     * @bodyParam status 港口状态0-禁止1-启用
     * @response {
     * }
     * @param Request $request
     * @param Port $port
     * @return Port|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Port $port)
    {
        $this->validate($request, [
            'name' => 'between:1,50',//
            'name_code' => 'between:1,50',//
            'country' => 'between:1,50',//
            'status' => 'boolean',
        ]);
        $port->id = $request->input('id');
        $request->input('name') !== null && $port->name = $request->input('name');
        $request->input('name_code') !== null && $port->name_code = $request->input('name_code');
        $request->input('country') !== null && $port->country = $request->input('country');
        $request->input('status') !== null && $port->status = $request->input('status');
        $port->save();
        return $port->where("id", $port->id)->with(['users'])->first();
    }

    /**
     * 删除00407
     * @bodyParam ids required 港口id,多个用英文逗号分割 Example:1,2,3,4
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Request $request)
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
            if (Port::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        Port::destroy(explode(",", $request->input('ids')));
        return [];//$port->refresh();
    }

    /**
     * 业务板块信息00409
     * @urlParam id required 港口id
     * @response {
     * }
     * @param Port $port
     * @return Business[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|mixed
     */
    public function show_business(Port $port)
    {
        $port = Port::query()->where('id', $port->id)->with(['segment_businesses', 'master_businesses', 'slaver_businesses'])->first();

        $return = $port->segment_businesses->map(function ($item, $key) use ($port) {
            $master_business = $port->master_businesses;
            $slaver_business = $port->slaver_businesses;
            $return = [];
            $return['user_name'] = call_user_func(function () use ($item) {
                $var = User::find(data_get($item, 'pivot.user_id'));
                if (empty($var)) {
                    return $var;
                }
                return data_get($var, 'name');
            });
            $return['updated_at'] = (string)data_get($item, "pivot.updated_at");;
            $return['segment_business_id'] = data_get($item, 'id');
            $return['segment_business_name'] = data_get($item, 'name');
            $return['segment_business_list'] = Business::query()->where("parent_id", 0)->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            $return['master_business_id'] = data_get($master_business[$key], 'id');
            $return['master_business_name'] = data_get($master_business[$key], 'name');
            $return['master_business_list'] = Business::query()->where("parent_id", $return['segment_business_id'])->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            $return['slaver_business_id'] = data_get($slaver_business[$key], 'id');
            $return['slaver_business_name'] = data_get($slaver_business[$key], 'name');
            $return['slaver_business_list'] = Business::query()->where("parent_id", $return['master_business_id'])->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            return $return;
        });

        return $return;
    }
}
