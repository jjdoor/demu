<?php

namespace App\Http\Controllers;

use App\ClearCompany;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group 【废弃】结算公司003
 */
class ClearCompanyController extends Controller
{
    /**
     * 列表00301
     *
     * @bodyParam page int 第几页，默认第一页 Example: 1
     * @bodyParam per_page int 每页记录数，默认是10 Example: 10
     * @bodyParam search string 模糊搜索
     * @bodyParam status int 0:禁用,1:启用. Example: 1
     * @bodyParam operate_user_id int 操作人
     * @response {
     *  "data":[{
     *  "id":4,
     *  "name":"结算公司全称",
     *  "name_abbreviation":"结算公司简称",
     *  "name_code":"结算公司助记码",
     *  "status":"禁止、启用",
     *  "created_at":"生成时间",
     *  "updated_at":"修改时间"
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
     *  "total": 50,
     *  "users":[{
     *      "id":4,
     *      "name":"benjamin"
     *  }]
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return \Illuminate\Pagination\AbstractPaginator
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'between:1,50',//|required
            'status' => 'boolean',
            'operate_user_id' => 'integer',
        ]);
        $list = ClearCompany::query()->with(['users'])->when($request->input('search'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where(function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('name', 'like', "%{$request->input('search')}%")
                    ->orWhere('name_code', 'like', "%{$request->input('search')}%")
                    ->orWhere('name_abbreviation', 'like', "%{$request->input('search')}%");
            });
        })->when($request->input('status') !== null, function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where('status', $request->input('status'));
        })->when($request->input('operate_user_id'), function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->where('user_id', $request->input('operate_user_id'));
        })
            ->orderBy("updated_at", "desc")
            ->paginate($request->input('per_page', 10));
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            /** @var ClearCompany $list */
            $data = [
                'id' => data_get($list, 'id'),
                'name' => data_get($list, 'name'),
                'name_code' => data_get($list, 'name_code'),
                'name_abbreviation' => data_get($list, 'name_abbreviation'),
                'status' => data_get($list, 'status') ? "启用" : "禁用",
                'user_name' => data_get($list, 'users.name'),
                'created_at' => (string)data_get($list, 'created_at', null),
                'updated_at' => (string)data_get($list, 'updated_at', null),
            ];
            return $data;
        }))->toArray();

        $user_list = ClearCompany::with(['users'])->get()->map(function ($item, $key) {
            $return = [];
            $return['id'] = data_get($item, 'users.id');
            $return['name'] = data_get($item, 'users.name');
            return $return;
        })->unique('id');

        return array_merge($collection, ['users' => $user_list]);
    }

    /**
     * 插入00302
     *
     * @bodyParam name string required 结算公司名称 Example:结算公司名称
     * @bodyParam name_code string required 结算公司代码 Example:xxx
     * @bodyParam name_abbreviation string required 结算公司简称
     * @bodyParam status int required 状态0：禁止1：启用 Example:1
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return array
     * @throws
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|between:1,50',
            'name_code' => 'required|between:1,50',
            'name_abbreviation' => 'required|between:1,50',
            'status' => 'boolean'
        ]);
//        $validator = validator()->validate($request->toArray(),
//            [
//                'code' => 'required | mobile',
//                'title' => 'required',
//            ],
//            [
//                'code . required' => '这 :attribute 是无效的 . ',
//                'title . required' => '1:attribute1',
//                'code . mobile' => '这不是一个有效的手机号码',
//            ],
//            [
//                'code' => 'code of captcha',
//                'title' => '哈哈标题哈哈'
//            ]
//        );
//        \Validator::make()
        try {
            $clearCompany = new ClearCompany();
            $clearCompany->name = $request->input('name');
            $clearCompany->name_abbreviation = $request->input('name_abbreviation');
            $clearCompany->name_code = $request->input('name_code');
            $clearCompany->user_id = $request->get('user_id');
            $clearCompany->status = $request->input('status');
            $clearCompany->save();
        } catch (Exception $e) {
            throw new \Exception("插入错误");
        }

        return [];

    }

    /**
     * 详情00303
     *
     * @urlParam id requied 结算公司id
     * @response {
     *  "id": 4,
     *  "name": "结算公司名称",
     *  "name_code": "结算公司代码",
     *  "name_abbreviation": "结算公司简称",
     *  "status": "结算公司状态",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param ClearCompany $clearCompany
     * @return ClearCompany
     */
    public function show(ClearCompany $clearCompany)
    {
        return $clearCompany;
//        return \App\Http\Resources\ClearCompany::collection($clearCompany->toArray());
    }

    /**
     * 更新00304
     *
     * @urlParam clearCompany int 结算公司id
     * @bodyParam name string 结算公司名称
     * @bodyParam name_code string 结算公司代码
     * @bodyParam name_abbreviation string 结算公司简称
     * @bodyParam status int 结算公司状态 -1-删除0-禁止1-启用 Example:1
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @param ClearCompany $clearCompany
     * @return array
     * @throws Exception
     */
    public function update(Request $request, ClearCompany $clearCompany)
    {
        $this->validate($request, [
            'status' => 'boolean',
            'name' => 'between:1,50',
            'name_code' => 'between:1,50',
            'name_abbreviation' => 'between:1,50',
        ]);
        try {
            $request->has('name') && $request->input('name') && $clearCompany->name = $request->input('name');
            $request->has('name_code') && $request->input('name_code') && $clearCompany->name_code = $request->input('name_code');
            $request->has('name_abbreviation') && $request->input('name_abbreviation') && $clearCompany->name_abbreviation = $request->input('name_abbreviation');
            $request->has('status') && $request->input('status') && $clearCompany->status = $request->input('status');
            $clearCompany->user_id = $request->get('login_user_id');
            $clearCompany->save();
        } catch (Exception $e) {
//            throw new Exception("修改错误!");
//            return "修改错误";
            return ['修改醋五'];
        }

        return [];
    }

    /**
     * 删除00305
     *
     * @urlParam clearCompany required 结算公司id,多个id用英文逗号分割
     * @response {
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @param $clearCompany
     * @return array
     */
    public function destroy(Request $request, $clearCompany)
    {
        /** @noinspection PhpUndefinedClassInspection */
        DB::transaction(function () use ($clearCompany, $request) {
            $ids = explode(",", $clearCompany);
            ClearCompany::whereIn("id", $ids)->update(["user_id" => $request->get("user_id")]);
            ClearCompany::destroy($ids);
        });
        //fixme-benjamin 已经使用的无法删除
        return [];
    }

}
