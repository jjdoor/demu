<?php

namespace App\Http\Controllers;

use App\CompanyOrganize;
use Illuminate\Http\Request;

/**
 * @group 公司组织架构002
 */
class CompanyOrganizeController extends Controller
{
    /**
     * 列表00201
     *
     * @queryParam parent_id 父组织id，默认为0
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "组织架构名称",
     *  "status":"0-禁止1-启用",
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $paginate = \DB::table("company_organizes")->where("parent_id",$request->get('parent_id',0))
            ->where("status",">",-1)
            ->orderBy("updated_at","desc")
            ->paginate($request->get('per_page') ? : 10);
        return $paginate;
//        return \App\Http\Resources\CompanyOrganize::collection($paginate);
//        return CompanyOrganize::collection($paginate);
    }

    /**
     * 插入00202
     *
     * @bodyParam parent_id int 父组织id，默认为0
     * @bodyParam name string required 组织架构名称
     * @response {
     *  "id": 4,
     *  "name": "组织架构名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "status":"0-禁止1-启用"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     */
    public function store(Request $request)
    {
        $data = [
            'name'=>$request->get('name'),
            'parent_id'=>$request->get('parent_id',0),
            'status'=>1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
        ];
        $id = DB::table("company_organizes")->insertGetId($data);
        $collection = \DB::table('company_organizes')->where("id", $id)->get();
//        $collection = \App\CompanyOrganize::get()->where("id", $id);
//        $static = $collection->fresh();
        return CompanyOrganize::collection($collection);
    }

    /**
     * 详情00203
     *
     * @queryParam companyOrganize required 组织架构id
     * @response {
     *  "id": 4,
     *  "name": "组织架构名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyOrganize $companyOrganize)
    {
        return $companyOrganize->toArray();
    }

    /**
     * 更新00204
     *
     * @queryParam companyOrganize required 组织架构id
     * @queryParam parent_id required 组织架构父id
     * @queryParam name required 组织架构名称
     * @queryParam status 组织架构状态 0-禁止1-启用
     * @response {
     *  "id": 4,
     *  "parent_id":1,
     *  "name": "组织架构名称",
     *  "status":1,
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     */
    public function update(Request $request, CompanyOrganize $companyOrganize)
    {
        if($request->get('name')){
            $companyOrganize->name = $request->get('name');
        }
        if($request->get('status')){
            $companyOrganize->status = $request->get('status');
        }
        if($request->get('parent_id')){
            $companyOrganize->parent_id  = $request->get('parent_id');
        }
        $companyOrganize->save();

        $statics = CompanyOrganize::where("id", $companyOrganize->id)->get();
        return \App\Http\Resources\CompanyOrganize::collection($statics);
    }

    /**
     * 删除002005
     *
     * @queryParam companyOrganize required 组织架构id
     * @response {
     *  "id": 4,
     *  "name": "组织架构名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     */
    public function destroy(CompanyOrganize $companyOrganize)
    {
        $companyOrganize::destroy($companyOrganize->id);

        return $companyOrganize->toArray();
    }
}
