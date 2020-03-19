<?php

namespace App\Http\Controllers;

use App\Business;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group 品名030
 */
class ProductController extends Controller
{
    /**
     * 列表03001
     *
     * @bodyParam page int 第几页，默认第一页 Example:1
     * @bodyParam per_page int 每页记录数，默认是10 Example:10
     * @bodyParam search string 模糊搜索 Example:i
     * @bodyParam status int 状态0:禁用，1:启用 Example:1
     * @bodyParam id int 品名id
     * @bodyParam user_id int 操作人 Example:1
     * @bodyParam segment_business_id int 主业务板块
     * @bodyParam master_business_id int 主业务类型
     * @bodyParam slaver_business_id int 子业务类型
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "品名",
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
            'user_id' => 'integer',
            'id' => 'integer',
            'segment_business_id' => 'integer',
            'master_business_id' => 'integer',
            'slaver_business_id' => 'integer',
        ]);

        $list = Product::query()->with(['users', 'segment_businesses', 'master_businesses', 'slaver_businesses'])
            ->when($request->input('search') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where("name", "like", "%{$request->input('name')}%");
            })->when($request->input('status') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('status', $request->input('status'));
            })
            ->when($request->input('id') !== null, function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('id', $request->input('id'));
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
                'status' => data_get($list, 'status'),
                'status_alias' => data_get($list, 'status') ? "启用" : "禁用",
                'user_name' => data_get($list, 'users.name'),
                'created_at' => (string)data_get($list, 'created_at', null),
                'updated_at' => (string)data_get($list, 'updated_at', null),
            ];
            return $data;
        }))->toArray();

        $user_list = Product::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        $name = Product::query()->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'id');
            $return['value'] = data_get($item, 'name');
            return $return;
        })->unique('key')->values();

        return array_merge($collection, ['user' => $user_list], ['name' => $name]);
    }

    /**
     * 插入03002
     *
     * @bodyParam name string required 品名
     * @bodyParam status int required 状态 Example:1
     * @response {
     *  "id": 4,
     *  "name": "名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "status":"0-禁止1-启用"
     * }
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'between:1,50|required',//
            'status' => 'boolean|required',
        ]);
        $product = new Product();
        $product->name = $request->input('name');
        $product->status = $request->input('status');
        $product->user_id = $request->get('login_user_id');
        $product->save();
        return $product->where("id", $product->id)->with(['users'])->first();
    }

    /**
     * 更新03003
     * @urlParam product required 品名id
     * @bodyParam name 品名
     * @bodyParam status 品名状态0-禁止1-启用
     * @response {
     * }
     * @param Request $request
     * @param Product $product
     * @return Product|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'between:1,50',//
            'status' => 'boolean',
        ]);
        $request->input('name') !== null && $product->name = $request->input('name');
        $request->input('status') !== null && $product->status = $request->input('status');
        $product->save();
        return $product->where("id", $product->id)->with(['users'])->first();
    }

    /**
     * 删除03004
     * @bodyParam ids required 品名id,多个用英文逗号分割 Example:1,2,3,4
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
            if (Product::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        Product::destroy(explode(",", $request->input('ids')));
        return [];//$port->refresh();
    }

    /**
     * 状态修改03005
     *
     * @bodyParam ids string required 多个id使用英文逗号分割 Example:1,2,3,4
     * @bodyParam status int required 状态 Example:1
     * @response {
     *  "id": 4,
     *  "name": "品名",
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
            if (Product::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        Product::query()->whereIn("id", explode(",", $request->input('ids')))->update(['status' => $request->input('status')]);
        return [];
    }

    /**
     * 业务板块插入更新03006
     *
     * @urlParam id int required 港口名称id
     * @jsonParam segment_business_id int required 业务板块id
     * @jsonParam master_business_id int required 主业务id
     * @jsonParam slaver_business_id int required 子业务id
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @param Product $product
     * @return array
     */
    public function update_business(Request $request, Product $product)
    {
        $json = $request->getContent();
        $arr = json_decode($json, true);
        if ($arr === null) {
            return ['code' => 422, 'message' => 'json格式错误'];
        }

        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($request, $product) {
            $data = $request->getContent();
            $arr = json_decode($data, true);
            $ids = [];
            foreach ($arr as $k => $v) {
                $ids[$v['slaver_business_id']] = ['user_id' => $request->get('login_user_id'), 'segment_business_id' => $v['segment_business_id'], 'master_business_id' => $v['master_business_id']];
            }
            $product->slaver_businesses()->withTimestamps()->sync($ids);
        });
        return [];
    }

    /**
     * 业务板块信息03008
     * @urlParam product required 品名id
     * @response {
     * }
     * @param Product $product
     * @return \App\Business[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|mixed
     */
    public function show_business(Product $product)
    {
        $product = Product::query()->with(['users'])->where('id', $product->id)->with(['segment_businesses', 'master_businesses', 'slaver_businesses'])->first();
        $return = $product->segment_businesses->map(function ($item, $key) use ($product) {
            $master_business = $product->master_businesses;
            $slaver_business = $product->slaver_businesses;
            $return = [];
            $return['user_name'] = data_get(User::find(data_get($item, 'pivot.user_id')), 'name');
            $return['updated_at'] = (string)data_get($item, "pivot.updated_at");
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
