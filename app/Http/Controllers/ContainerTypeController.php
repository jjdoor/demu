<?php

namespace App\Http\Controllers;

use App\ContainerType;
use Illuminate\Http\Request;

/**
 * @group 箱型对应关系024
 * Class ContainerTypeController
 * @package App\Http\Controllers
 */
class ContainerTypeController extends Controller
{
    /**
     * 列表02401
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam keyword 搜索关键词
     * @queryParam category_id 箱子类别
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "箱型名称",
     *  "size": "箱型尺寸",
     *  "category_id": "箱型分类 1：小箱，2：大箱，3：特大箱，4：自然箱",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/containerTypes?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/containerTypes?page=5",
     *  "next_page_url": "http://host/api/v1/containerTypes?page=2",
     *  "path": "http://host/api/v1/containerTypes",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @response 404 {
     *  "message": "No query results"
     * }
     * @param Request $request
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function index(Request $request)
    {
        $list = ContainerType::query()->when($request->input('keyword') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('keyword')}%")->orWhere('size', 'like', "%{$request->input('keyword')}%");
                });
            })->when($request->input('category_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('category_id', $request->input('category_id'));
                });
            })
            ->orderBy("updated_at","desc")->paginate($request->input('per_page',10));
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list){
            /** @var ContainerType $list */
            $data = [
                'id'=>$list->id,
                'name'=>$list->name ?? null,
                'size' =>$list->size ?? null,
                'category_id'=>$list->category_id ?? 0,
                'created_at'=>(string)data_get($list,'created_at',null),
                'updated_at'=>(string)data_get($list,'updated_at',null),
            ];
            return $data;
        }));
        return $collection;
    }

    /**
     * 插入02402
     *
     * @bodyParam name string required 箱型名称
     * @bodyParam size string required 箱型尺寸
     * @bodyParam category_id int category_id 箱型分类 1：小箱，2：大箱，3：特大箱，4：自然箱
     * @response {
     * }
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $containerType = new ContainerType();
        $containerType->name = $request->input('name');
        $containerType->size = $request->input('size');
        $containerType->category_id = $request->input('category_id');
        $containerType->save();
        return $containerType->where("id", $containerType->id)->first();
    }

    /**
     * 详情02403
     *
     * @queryParam containerType required 箱型id
     * @response {
     *  "id": 4,
     *  "name": "箱型名称",
     *  "size": "箱型尺寸",
     *  "category_id": "箱型分类 1：小箱，2：大箱，3：特大箱，4：自然箱",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @param ContainerType $containerType
     * @return ContainerType
     */
    public function show(ContainerType $containerType)
    {
        return $containerType;
    }

    /**
     * 更新02404
     * @queryParam id required 箱型id
     * @queryParam name 箱型名称
     * @queryParam size 箱型尺寸
     * @queryParam category_id 箱型分类 1：小箱，2：大箱，3：特大箱，4：自然箱
     * @response {
     * }
     * @param Request $request
     * @param ContainerType $containerType
     * @return array
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'between:1,60|required',
            'size' => 'between:1,50|required',
            'category_id' => 'between:1,2|required',
        ]);
        $ContainerType = ContainerType::find($request->input('id'));
        $ContainerType->name = $request->input('name');
        $ContainerType->size = $request->input('size');
        $ContainerType->category_id = $request->input('category_id');
        $ContainerType->save();
        return $ContainerType->where("id", $ContainerType->id)->first();
    }

    /**
     * 删除00705
     * @queryParam ids required 箱型id多个用英文逗号分割 Example:1,2,3,4
     * @response {
     * }
     * @param ids $ids
     * @return array
     */
    public function destroy(Request $request)
    {
        // ContainerType::destroy($containerType->id);
        // return [];

         $str = explode(",",$request->input('ids'));
        foreach($str as $v){
            ContainerType::query()->where('id',"=","$v")->delete();
        }
        return [];



    }
}
