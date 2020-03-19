<?php

namespace App\Http\Controllers;

use App\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @group 主业务类型维护001
 */
class BusinessController extends Controller
{
    /**
     * 列表00101
     *
     * @queryParam page int 第几页，默认为1
     * @queryParam per_page int 每页数，默认为10
     * @response {
     * "data":[{
     *  "id": 4,
     *  "segment_id":"板块id",
     *  "segment_name":"板块名称",
     *  "segment_status":"板块状态",
     *  "master_id":"主业务id",
     *  "master_name":"主业务名称",
     *  "master_status":"主业务状态",
     *  "slaver_id":"子业务id",
     *  "slaver_name": "子业务名称",
     *  "slaver_status":"子业务状态0-禁止1-启用",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
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
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
//        $business = Business::query()->with(['master_businesses'=>function($q){
//            /** @type \Illuminate\Database\Eloquent\Builder $q */
//            $q->with(['slaver_businesses']);
//        }])->where('parent_id',0)->orderBy("updated_at","desc")->paginate($request->get('per_page',10));
        $business = Business::query()->select(['businesses.id as segment_id',
            'businesses.name as segment_name',
            'businesses.status as segment_status',
            'master.id as master_id',
            'master.name as master_name',
            'master.status as master_status',
            'slaver.id as slver_id',
            'slaver.name as slaver_name',
            'slaver.status as slaver_status'])
            ->leftJoinSub("select * from businesses where deleted_at is null and (status=1 or status is null)", "master", "businesses.id", '=', "master.parent_id")
            ->leftJoinSub("select * from businesses where deleted_at is null and (status=1 or status is null)", "slaver", "master.id", '=', "slaver.parent_id")
            ->where('businesses.parent_id', 0)->where('businesses.status', 1)
            ->orderBy("businesses.id", "desc")
            ->orderBy("master.id", "desc")
            ->orderBy("slaver.id", "desc")->paginate($request->get('per_page', 10));
        return $business;
        //        $business = Business::where("id",">",1)->orderBy("updated_at","desc")->paginate($request->get('per_page',10));
//        $anonymousResourceCollection = \App\Http\Resources\Business::collection($business);
//        return $anonymousResourceCollection;
    }

    /**
     * 树形列表00107
     *
     * @queryParam page int 第几页，默认为1
     * @queryParam per_page int 每页数，默认为10
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name":"业务板块名称",
     *  "master_business":[
     *      {
     *          "id":5,
     *          "name":"主业务板块",
     *          "slaver_business":[
     *              {
     *                  "id":6,
     *                  "name":"子业务板块"
     *              }
     *          ]
     *      }
     *  ]
     * }]
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    function list()
    {
        $business = Business::query()->with(['master_business' => function ($q) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->with(['slaver_business' => function ($q) {
                /** @type Builder $q */
                $q->where('status', 1)->orderBy('updated_at', "desc");
            }])->where('status', 1)->orderBy("updated_at", "desc");
        }])->where('parent_id', 0)->where('status', 1)->orderBy("updated_at", "desc")->get();

        return $business;
    }


    /**
     * 父节点下子节点列表00106
     * @queryParam parent_id int 父节点，默认为0(获取最上层节点也就是板块)
     * @response[
     *  {
     *      "id":"1",
     *      "name":"节点名字"
     * }
     * ]
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    function listResult(Request $request)
    {
        $parent_id = $request->input('parent_id', 0);
        $statics = Business::where('parent_id', $parent_id)->where('status', 1)->get();
        return $statics;
    }

    /**
     * 插入00102
     *
     * @bodyParam parent_id int 父业务类型id，默认为0
     * @bodyParam name string required 业务类型
     * @response {
     *  "id": 4,
     *  "name": "业务类型",
     *  "parent_id":"主业务id",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "status":"0-禁止1-启用"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $business = new Business();
        $business->name = $request->input('name');
        $business->parent_id = $request->input('parent_id', 0);
        $business->save();
        return $business->attributesToArray();
    }

    /**
     * 显示00103
     * @queryParam $business required 业务id
     * @response {
     * "id":1,
     * "name":"业务名称",
     * "parent_id":0,
     * "created_at":"2019-06-12 07:48:10",
     * "updated_at":"2019-06-12 07:48:10"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Business $business
     * @return array
     */
    public function show(Business $business)
    {
        return $business->toArray();
    }

    /**
     * 更新00104
     * @queryParam business required 业务id
     * @bodyParam parent_id 父业务id，默认是0,0=>板块=>主业务=>子业务(按照传统的父子节点设计的)
     * @bodyParam name string 业务名称
     * @bodyParam status int 业务状态
     * @response {
     * "id":1,
     * "name":"业务名称",
     * "parent_id":0,
     * "created_at":"2019-06-12 07:48:10",
     * "updated_at":"2019-06-12 07:48:10"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @param Business $business
     * @return array
     */
    public function update(Request $request, Business $business)
    {
        $request->has('parent_id') && $business->parent_id = $request->input('parent_id');
        $request->has('name') && $business->name = $request->input('name');
        $request->has('status') && $business->status = $request->input('status');
        $business->save();
        return $business->attributesToArray();
        //
//        $model = Business::find($id);
//        $model->parent_id = $request->input('parent_id');
//        $model->name = $request->input('name');
//        $model->status = $request->input('status');
//        $model->save();
//
//        return $model->attributesToArray();
    }

    /**
     * 删除00105
     * @queryParam business required 业务id
     * @response {
     *
     * }
     *
     * @param Business $business
     * @return array
     * @throws \Exception
     */
    public function destroy(Business $business)
    {
        //
//        $request->validate([
//            'id' => 'required|unique:posts|max:255',
//        ]);
        if (Business::query()->where('parent_id', $business->id)->count()) {
            throw new \Exception('该业务下存在子业务，不可删除，可先逐条删除子业务，方可删除该业务');
        }
        Business::destroy($business->id);
        return [];
    }
}
