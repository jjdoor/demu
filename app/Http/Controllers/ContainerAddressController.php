<?php

namespace App\Http\Controllers;

use App\ContainerAddress;
use App\ContainerAddressData;
use App\Business;
use Illuminate\Http\Request;

/**
 * @group 装箱送箱地点025
 */
class ContainerAddressController extends Controller
{
    /**
     * 列表02501
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam address_id 检索关键词
     * @queryParam status  状态 0：禁用，1：启用
     * @queryParam user_id 操作人
     * @queryParam segment_business_id 业务板块
     * @queryParam master_business_id 主业务板块
     * @queryParam slaver_business_id 子业务板块
     * @queryParam category_status 箱子状态 0：装箱，1：送箱，2：还箱 默认0
     * @response {
     * "data":[{
     *  "id": 4,
     *  "address": "地址",
     *  "category_status": "是否装箱地点0:否，1:是",
     *  "status": "0:禁用，1:启用",
     *  "handle_man": "操作人",
     *  "updated_at": "操作时间",
     *  "container_address_data":{
     *      "segment_businesses_id":"业务板块id",
     *      "segment_businesses_name":"业务板块名称",
     *      "master_businesses_id":"主业务id",
     *      "master_businesses_name":"主业务名称",
     *      "slaver_businesses_id":"子业务id",
     *      "slaver_businesses_name":"子业务名称"
     * }
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
        $category_status=0;

        if(is_numeric($request->input('category_status'))&&strpos($request->input('category_status'),".")==false){
            $category_status= $request->input('category_status');
        }
        $list = ContainerAddress::query()->with(['users','container_address_data'=>function($q) use ($request){
            $q->with(['segment_businesses', 'master_businesses', 'slaver_businesses']);

           }])
           ->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
              $q->whereHas('container_address_data', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('segment_business_id', $request->input('segment_business_id'));
              });
           })
           ->when($request->input('master_business_id') !== null, function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
              $q->whereHas('container_address_data', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('master_business_id', $request->input('master_business_id'));
              });
           })
           ->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
            /** @type \Illuminate\Database\Eloquent\Builder $q */
              $q->whereHas('container_address_data', function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
              });
           })
           ->when($request->input('address_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('id', $request->input('address_id'));
                });
            })
           ->when($request->input('status') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                });
            })
           ->when($request->input('user_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('user_id', $request->input('user_id'));
                });
            })
           ->when($request->input('category_status') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {

                    $q->where('category_status',$request->input('category_status'));
                });
            })

            ->orderBy("updated_at","desc")->paginate($request->input('per_page',10));

        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list){
            /** @var ContainerAddress $list */
            $container_address_data = collect($list->container_address_data)->map(function($item,$key){
                $data['segment_businesses_id'] = data_get($item,'segment_businesses.id');
                $data['segment_businesses_name'] = data_get($item,'segment_businesses.name');
                $data['master_businesses_id'] = data_get($item,'master_businesses.id');
                $data['master_businesses_name'] = data_get($item,'master_businesses.name');
                $data['slaver_businesses_id'] = data_get($item,'slaver_businesses.id');
                $data['slaver_businesses_name'] = data_get($item,'slaver_businesses.name');
                return $data;
            });

            $data = [
                'id'=>$list->id,
                'status'=>$list->status ?? '',
                'address' =>$list->address ?? '',
                'category_status' => $list->category_status ?? '',
                'handle_man'=> $list->users->name ?? '',
                'updated_at'=> (string)$list->updated_at ?? '',
                'container_address_data'=>$container_address_data,
            ];
            return $data;
        }))->toArray();

        //获取用户信息
        $user_list = ContainerAddress::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
       //获取containeraddress表数据
        $address_list1 = ContainerAddress::query()->select('id','address')->get()->map(function ($item) {
            $return1 = [];
            $return1['id'] = data_get($item, 'id');
            $return1['address'] = data_get($item, 'address');
            return $return1;
        })->toArray();
        $new_key=array();
        $address_list=array();
        foreach ($address_list1 as $key=>$v) {
            if(!in_array($v['address'],$new_key)){
                $new_key[]=$v['address'];
                $v1['id']=$v['id'];
                $v1['address']=$v['address'];
                $address_list[]=$v1;
            }
        }

        $status_arr = [['key'=>0,'value'=>'禁用'],['key'=>1,'value'=>'启用']];

        return array_merge($collection, ['user' => $user_list],['status' => $status_arr],['addresslist' => $address_list]);
        //return $collection;
    }


    /**
     * 列表-新增02502
     * @bodyParam address string required 地址
     * @bodyParam category_status bool required 箱子维护状态 0：装箱，1：送箱，2：还箱
     * @bodyParam status bool required 状态0:禁用,1:启用
     * @response {
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listAdd(Request $request)
    {
        $this->validate($request, [
            'address' => 'between:1,50|required',
            'category_status' => 'integer|required',
            'status' => 'boolean|required',
        ]);
        $containerAddress = new ContainerAddress();
        $containerAddress->address = $request->input('address');
        $containerAddress->category_status = $request->input('category_status');
        $containerAddress->status = $request->input('status');
        $containerAddress->user_id = $request->get('login_user_id');
        $containerAddress->save();
        return $containerAddress->where("id", $containerAddress->id)->first();;
    }


    /**
     * 详情02503
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam id required 装箱送箱地点id
     * @response {
     *  "data":[{
     *      "id":1,
     *      "user_name":"操作人",
     *      "updated_at":"操作时间",
     *      "container_address_id":"箱子地店id",
     *      "segment_businesses_id":"业务板块id",
     *      "segment_businesses_name":"业务板块名称",
     *      "master_businesses_id":"主业务板块id",
     *      "master_businesses_name":"主业务板块名称",
     *      "slaver_businesses_id":"子业务板块id",
     *      "slaver_businesses_name":"子业务板块名称"
     *  }]
     * }
     * @param id $id
     * @return ContainerType
     */
    public function businessShow(Request $request,$containerid)
    {

      $data_list = ContainerAddressData::query()->with(['users'])->where("container_address_id",$containerid) ->orderBy("updated_at","desc")->get();

        //对数据处理
        $collection = $data_list->map(function ($data_list) use ($request){
                //获取业务数据
                $business_arr = Business::query()->get(['id','name'])->toArray();
                foreach ($business_arr as $value) {

                    if($data_list->segment_business_id==$value['id']){
                       $segment_businesses_name=$value['name'];

                    }
                    if($data_list->master_business_id==$value['id']){
                       $master_businesses_name=$value['name'];
                    }
                    if($data_list->slaver_business_id==$value['id']){
                       $slaver_businesses_name=$value['name'];
                    }

                }

              $segment_business_list=Business::query()->where("parent_id", 0)->get()->map(function ($item) {
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        return $return;
                 });

              $master_business_list=Business::query()->where("parent_id", $data_list->segment_business_id)->get()->map(function ($item) {
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        return $return;
                 });

              $slaver_business_list=Business::query()->where("parent_id", $data_list->master_business_id)->get()->map(function ($item) {
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        return $return;
               });

            $data= [

                'id' => $data_list->id,
                'user_name' => $data_list->users->name ?? null,
                'updated_at' => (string)$data_list->updated_at,
                'container_address_id' => $data_list->container_address_id,
                'segment_business_id' => $data_list->segment_business_id,
                'segment_business_name' =>$segment_businesses_name ?? null,
                'segment_business_list' => $segment_business_list,
                'master_business_id' => $data_list->master_business_id,
                'master_business_name' =>$master_businesses_name ?? null,
                'master_business_list' =>$master_business_list,
                'slaver_business_id' => $data_list->slaver_business_id,
                'slaver_business_name' =>$slaver_businesses_name ?? null,
                'slaver_business_list' => $slaver_business_list,

            ];
            return $data;
        });

        return $collection;
    }

    /**
     * 列表-更新02504
     * @queryParam id required 装箱送箱地点id
     * @queryParam address 地址
     * @category_status bool required 箱子维护状态 0：装箱，1：送箱，2：还箱
     * @queryParam status 状态 0：禁用，1:启用
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function listUpdate(Request $request)
    {

           $this->validate($request, [
                'address' => 'between:1,80|required',
                'category_status' => 'integer|required',
                'status' => 'boolean|required',
            ]);
            $containerAddress = containerAddress::find($request->input('id'));
            $containerAddress->address = $request->input('address');
            $containerAddress->is_up = $request->input('is_up');
            $containerAddress->is_down = $request->input('is_down');
            $containerAddress->status = $request->input('status');
            $containerAddress->user_id = $request->get('login_user_id');
            $containerAddress->save();
            return $containerAddress->where("id", $containerAddress->id)->first();

    }

    /**
     * 删除02505
     * @queryParam ids required 装箱送箱地点id 多个用英文逗号分割
     * @response {
     * }
     * @param equest $request
     * @return array
     */
    public function destroy(Request $request)
    {

        $ids=$request->input('ids');
        $ids_arr=explode(",",$ids);
        foreach ($ids_arr as $value) {
              ContainerAddress::destroy($value); //删除containeraddress表数据
                //删除containeraddressData数据
              ContainerAddressData::query()->where("container_address_id",$value)->delete();
           }
        return [];
    }

    /**
     * 详情-更新02506
     * @queryParam container_addresses_id required 箱子地点id
     * @bodyParam segment_businesses_id int required 业务板块id，多个用数组方式
     * @bodyParam master_businesses_id int required 主业务类型id，多个用数组方式
     * @bodyParam slaver_businesses_id int required 子业务类型id，多个用数组方式
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function detailsUpdate(Request $request,$addressid)
    {

            //删除container_address_id关联数据
            ContainerAddressData::query()->where("container_address_id",$addressid)->delete();
            foreach ($request->all() as $value) {
                $contractData = new ContainerAddressData();
                $contractData->container_address_id = $addressid;
                $contractData->segment_business_id = $value['segment_business_id'];
                $contractData->master_business_id = $value['master_business_id'];
                $contractData->slaver_business_id = $value['slaver_business_id'];
                $contractData->user_id = $request->get('login_user_id');
                $contractData->save();
            }

            return [];

    }


    /**
     * 列表-状态更新02507
     * @queryParam ids required 箱子地点id 多个用英文逗号分割
     * @queryParam status 状态 0：禁用，1:启用
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Throwable
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
            if (ContainerAddress::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);
        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        ContainerAddress::query()->whereIn("id", $ids_arr)->update(['status'=> $request->input('status')]);
        return [];


    }



}

