<?php

namespace App\Http\Controllers;

use App\PriceMaintain;
use App\PriceMaintainData;
use App\Business;
use Illuminate\Http\Request;

/**
 * @group 价格条件字段维护028
 */
class PriceMaintainController extends Controller
{
  /**
     * 列表02801
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam search_id 检索关键词
     * @queryParam status  状态 0：禁用，1：启用
     * @queryParam user_id 操作人
     * @queryParam segment_business_id 业务板块
     * @queryParam master_business_id 主业务板块
     * @queryParam slaver_business_id 子业务板块
     * @queryParam is_charging 是否计费单位标志 0：否，1：是
     * @queryParam is_price_conditions 是否价格条件标志 0：否，1：是
     * @queryParam is_payment_source 是否收付单位来源标志  0：否，1：是
     * @response {
     * "data":[{
     *  "id": 4,
     *  "business_price": "业务价格条件字段",
     *  "status": "0:禁用，1:启用",
     *  "user_name": "操作人",
     *  "updated_at": "操作时间",
     *  "is_charging": "是否计费单位标志",
     *  "is_price_conditions": "是否价格条件标志",
     *  "is_payment_source": "是否收付单位来源标志",
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

        $list = PriceMaintain::query()->with(['users','price_maintain_data'=>function($q) use ($request){
            $q->with(['segment_businesses', 'master_businesses', 'slaver_businesses']);
           }])
           ->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
            
              $q->whereHas('price_maintain_data', function ($q) use ($request) {
                
                $q->where('segment_business_id', $request->input('segment_business_id'));
              });
           })
           ->when($request->input('master_business_id') !== null, function ($q) use ($request) {
            
              $q->whereHas('price_maintain_data', function ($q) use ($request) {
                
                $q->where('master_business_id', $request->input('master_business_id'));
              });
           })
           ->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
            
              $q->whereHas('price_maintain_data', function ($q) use ($request) {
                
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
              });
           })
        ->when($request->input('search_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('id', $request->input('search_id'));
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
            ->when($request->input('is_charging') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('is_charging', $request->input('is_charging'));
                });
            })
             ->when($request->input('is_price_conditions') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('is_price_conditions', $request->input('is_price_conditions'));
                });
            })
              ->when($request->input('is_payment_source') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('is_payment_source', $request->input('is_payment_source'));
                });
            })
            ->orderBy("updated_at","desc")->paginate($request->input('per_page',10));

        
        $collection = $list->setCollection($list->getCollection()->map(function ($list){

            // $price_maintain_data = collect($list->price_maintain_data)->map(function($item,$key){
            //     $data['segment_businesses_id'] = data_get($item,'segment_businesses.id');
            //     $data['segment_businesses_name'] = data_get($item,'segment_businesses.name');
            //     $data['master_businesses_id'] = data_get($item,'master_businesses.id');
            //     $data['master_businesses_name'] = data_get($item,'master_businesses.name');
            //     $data['slaver_businesses_id'] = data_get($item,'slaver_businesses.id');
            //     $data['slaver_businesses_name'] = data_get($item,'slaver_businesses.name');
            //     return $data;
            // });

            $data = [
                'id'=>$list->id,
                'status'=>$list->status ?? '',
                'business_price' =>$list->business_price ?? '',
                'is_charging' =>$list->is_charging ?? '',
                'is_price_conditions' =>$list->is_price_conditions ?? '',
                'is_payment_source' =>$list->is_payment_source ?? '',
                'user_name'=> $list->users->name ?? '',
                'updated_at'=> (string)$list->updated_at ?? '',
            ];
            return $data;
        }))->toArray();

        //获取用户信息
        $user_list = PriceMaintain::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
       //获取price_maintain表数据
        $price_list = PriceMaintain::query()->select('id','business_price')->get()->map(function ($item) {
            $return1 = [];
            $return1['key'] = data_get($item, 'id');
            $return1['value'] = data_get($item, 'business_price');
            return $return1;
        });

        $status_arr = [['key'=>0,'value'=>'禁用'],['key'=>1,'value'=>'启用']];
            
        return array_merge($collection, ['user' => $user_list],['status' => $status_arr],['pricelist' => $price_list]);
    
    }


    /**
     * 详情02802
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam id required 价格字段id
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
     * @return PriceMaintain
     */
    public function priceShow(Request $request,$priceid)
    {

      $data_list = PriceMaintainData::query()->with(['users'])->where("price_maintain_id",$priceid) ->orderBy("updated_at","desc")->get();

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
              //获取业务板块
              $segment_business_list=Business::query()->where("parent_id", 0)->get()->map(function ($item) {
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        return $return;
                 });
              //获取主业务板块
              $master_business_list=Business::query()->where("parent_id", $data_list->segment_business_id)->get()->map(function ($item) {
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        return $return;
                 });
              //获取子业务板块
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
                'price_maintain_id' => $data_list->price_maintain_id,
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
     * 详情-更新02803
     * @queryParam price_maintain_id required 价格条件id
     * @bodyParam segment_businesses_id int required 业务板块id，多个用数组方式
     * @bodyParam master_businesses_id int required 主业务类型id，多个用数组方式
     * @bodyParam slaver_businesses_id int required 子业务类型id，多个用数组方式
     * @response {
     * }
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function detailsUpdate(Request $request,$priceid)
    {

        //删除price_maintain_id关联数据
        PriceMaintainData::query()->where("price_maintain_id",$priceid)->delete();
        foreach ($request->all() as $value) {
            $PriceMaintainData = new PriceMaintainData();
            $PriceMaintainData->price_maintain_id = $priceid;
            $PriceMaintainData->segment_business_id = $value['segment_business_id'];
            $PriceMaintainData->master_business_id = $value['master_business_id'];
            $PriceMaintainData->slaver_business_id = $value['slaver_business_id'];
            $PriceMaintainData->user_id = $request->get('login_user_id');
            $PriceMaintainData->save();
        }

            return [];
    }


    /**
     * 状态更新02804
     * @queryParam ids required 价格条件id 多个用英文逗号分割
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
            if (PriceMaintain::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);
        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        PriceMaintain::query()->whereIn("id", $ids_arr)->update(['status'=> $request->input('status')]);
        return [];

        
    }
}
