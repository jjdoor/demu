<?php
    /**
     * Author: jkwu
     * Date: 2019/8/22
     * Time: 15:27
     * Description:
     */
namespace App\Http\Controllers;

use App\ChargeItem;
use App\ChargeItemTaxRate;
use App\Business;
use App\CustomerSupplier;
use App\InvoiceType;
use App\Service\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @group 费用科目019
 */
class ChargeItemController extends Controller
{
    /**
     * 列表01901
     *
     * @queryParam page 第几页，默认为1
     * @queryParam per_page 每页数，默认为10
     * @queryParam status 状态 0禁用，1启用
     * @queryParam cost_code 费用科目代码
     * @queryParam cost_name 费用科目名称
     * @queryParam currency_id 默认币别
     * @queryParam user_id 操作人
     * @queryParam segment_business_id 业务板块
     * @queryParam master_business_id 主业务板块
     * @queryParam slaver_business_id 子业务板块
     * @queryParam is_tax_free 是否免税
     * @queryParam settlement_company_id 结算公司
     * @response {
     * "data":[{
     *       "id": 4,
     *       "status": "业务板块名称",
     *       "code": "费用科目代码",
     *       "name": "费用科目名称",
     *       "currency_code": "默认币别",
     *       "user_name": "操作人",
     *       "updated_at": "操作时间",
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=5",
     *  "next_page_url": "http://demu.tao3w.com/api/v1/chargeItemTaxRates?page=2",
     *  "path": "http://demu.tao3w.com/api/v1/chargeItemTaxRates",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @param Request $request
     * @return \Illuminate\Pagination\AbstractPaginator
     */
    public function index(Request $request)
    {

          $list = ChargeItem::query()->with(['users','charge_item_tax_rate'=>function($q) use ($request){
            $q->with(['segment_businesses', 'master_businesses', 'slaver_businesses','customer_suppliers']);

           }])
           ->when($request->input('segment_business_id') !== null, function ($q) use ($request) {
              $q->whereHas('charge_item_tax_rate', function ($q) use ($request) {
                $q->where('segment_business_id', $request->input('segment_business_id'));
              });
           })
           ->when($request->input('master_business_id') !== null, function ($q) use ($request) {
              $q->whereHas('charge_item_tax_rate', function ($q) use ($request) {
                $q->where('master_business_id', $request->input('master_business_id'));
              });
           })
           ->when($request->input('slaver_business_id') !== null, function ($q) use ($request) {
              $q->whereHas('charge_item_tax_rate', function ($q) use ($request) {
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
              });
           })
           ->when($request->input('cost_code') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('code', $request->input('cost_code'));
                });
            })
           ->when($request->input('cost_name') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', $request->input('cost_name'));
                });
            })
           ->when($request->input('user_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('user_id', $request->input('user_id'));
                });
            })
           ->when($request->input('status') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                });
            })
           ->when($request->input('currency_id') !== null, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                     $q->where('currency_code', $request->input('currency_id'));
                });
            })
           ->when($request->input('is_tax_free') !== null, function ($q) use ($request) {
                $q->whereHas('charge_item_tax_rate', function ($q) use ($request) {
                     $q->where('is_tax_free', $request->input('is_tax_free'));
                });
            })
            ->when($request->input('settlement_company_id') !== null, function ($q) use ($request) {
                $q->whereHas('charge_item_tax_rate', function ($q) use ($request) {
                     $q->where('customer_suppliers_id', $request->input('settlement_company_id'));
                });
            })

            ->orderBy("updated_at","desc")->paginate($request->input('per_page',10));


        $collection = $list->setCollection($list->getCollection()->map(function ($list){

            $data = [
                'id'=>$list->id,
                'status'=>$list->status ?? '',
                'code' =>$list->code ?? '',
                'name' => $list->name ?? '',
                'user_name'=> $list->users->name ?? '',
                'user_id'=> $list->users->id ?? '',
                'currency_code'=> $list->currency_code ?? '',
                'updated_at'=> (string)$list->updated_at ?? '',
            ];

            return $data;
        }))->toArray();

        //获取用户信息
        $user_list = ChargeItem::with(['users'])->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'users.id');
            $return['value'] = data_get($item, 'users.name');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();
       //获取charge_itme表数据
        $charge_item_arr = ChargeItem::query()->select('id','code','name')->get()->map(function ($item) {
            $return1 = [];
            $return1['id'] = data_get($item, 'id');
            $return1['code'] = data_get($item, 'code');
            $return1['name'] = data_get($item, 'name');
            return $return1;
        });
        //获取结算公司数据
        $customer_suppliers_arr = CustomerSupplier::query()->where(['is_self'=>1,'deleted_at'=>null])->select('id','name_abbreviation')->get()->map(function ($item) {
            $return = [];
            $return['key'] = data_get($item, 'id');
            $return['value'] = data_get($item, 'name_abbreviation');
            return $return;
        })->reject(function ($item) {
            return $item['key'] === null;
        })->unique('key')->values();

        //开票类型税率
        $invoice_type_arr=InvoiceType::query()->select(['id','name','tax_rate'])->get()->map(function ($item){
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                $return['tax_rate'] = data_get($item, 'tax_rate');
                return $return;
        });


        //状态
        $status_arr = [['key'=>0,'value'=>'禁用'],['key'=>1,'value'=>'启用']];
        //是否免税
        $is_tax_free_arr = [['key'=>0,'value'=>'否'],['key'=>1,'value'=>'是']];
        //是否免税1:USD,2:CNY
        $currency_code_arr = [['key'=>1,'value'=>'USD'],['key'=>2,'value'=>'CNY']];
        return array_merge($collection, ['user' => $user_list],['status' => $status_arr],['is_tax_free' => $is_tax_free_arr],['charge_item' => $charge_item_arr],['customer_suppliers_list'=>$customer_suppliers_arr],['invoice_type_list'=>$invoice_type_arr]);
        return $result;
    }

    /**
     * 插入01902
     * @bodyParam code string required 费用科目代码
     * @bodyParam name string required 费用科目名称
     * @bodyParam currency_code string required 1:USD,2:CNY
     * @bodyParam status boolean required 0:禁用，1：启用
     * @response 200{
     * }
     * @param Request $request
     * @return array
     */
    public function chargeItemAdd(Request $request)
    {

        $request->validate([
            'code' => 'alpha|required',
            'name' => 'between:1,30|required',
            'currency_code' => 'integer|required',
            'status' => 'boolean|required',
        ]);
        $chargeItem = new ChargeItem();
        $chargeItem->code = $request->input('code');
        $chargeItem->name = $request->input('name');
        $chargeItem->currency_code = $request->input('currency_code');
        $chargeItem->status = $request->input('status');
        $chargeItem->user_id = $request->get('login_user_id');
        $chargeItem->save();
        return [];
    }


    /**
     * 列表修改01903
     *
     * @bodyParam code string required 费用科目代码
     * @bodyParam name string required 费用科目名称
     * @bodyParam currency_code int required 1:USD,2:CNY
     * @bodyParam status boolean required 0:禁用，1：启用
     * @response 200{
     * }
     * @param Request $request $id
     * @return array
     */
    public function chargeItemUpdate(Request $request,$id)
    {

        $request->validate([
            'code' => 'alpha|required',
            'name' => 'between:1,30|required',
            'currency_code' => 'integer|required',
            'status' => 'boolean|required',
        ]);
        $chargeItem = ChargeItem::find($id);
        $chargeItem->code = $request->input('code');
        $chargeItem->name = $request->input('name');
        $chargeItem->currency_code = $request->input('currency_code');
        $chargeItem->status = $request->input('status');
        $chargeItem->user_id = $request->get('login_user_id');
        $chargeItem->save();
        return [];

    }

     /**
     * 状态更新01904
     * @queryParam ids required 多个用英文逗号分割
     * @queryParam status required 0禁用，1启用
     * @response 200{
     * }
     * @param chargeItem $chargeItem
     * @return array
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
            if (chargeItem::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);
        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        chargeItem::query()->whereIn("id", $ids_arr)->update(['status'=> $request->input('status')]);
        return [];
    }




   /**
     * 删除01905
     * @queryParam ids required 费用科目id 多个用英文逗号分割
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
              //删除chargeitem表数据
              ChargeItem::destroy($value);
              //删除chargeitemtaxData数据
              ChargeItemTaxRate::query()->where("charge_item_id",$value)->delete();
           }
        return [];
    }


    /**
     * 详情01906
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @queryParam id required 费用科目id
     * @response {
     *  "data":[{
     *      "id":1,
     *      "user_name":"操作人",
     *      "updated_at":"操作时间",
     *      "charge_ltem_id":"费用科目id",
     *      "segment_businesses_id":"业务板块id",
     *      "segment_businesses_name":"业务板块名称",
     *      "segment_business_list"：[],
     *      "master_businesses_id":"主业务板块id",
     *      "master_businesses_name":"主业务板块名称",
     *      "master_business_list":[],
     *      "slaver_businesses_id":"子业务板块id",
     *      "slaver_businesses_name":"子业务板块名称"
     *      "slaver_business_list":[],
     *      "customer_suppliers_id":"结算公司id",
     *      "name_abbreviation":"结算公司名称",
     *      "customer_supplier_list":[],
     *      "invoice_types_id":"开票类型税率id",
     *      "invoice_name":"开票类型",
     *      "tax_rate":"开票税率",
     *      "invoice_type_list":[],
     *      "is_tax_free":"是否免税",
     *
     *  }]
     * }
     * @param id $id
     * @return array
     */
    public function chargeItemShow(Request $request,$id)
    {

      $data_list = ChargeItemTaxRate::query()->with(['users'])->where("charge_item_id",$id) ->orderBy("updated_at","desc")->get();

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
              //获取客户供应商
              global $name_abbreviation;
              $customer_supplier_arr=CustomerSupplier::query()->where(['is_self'=>1,'deleted_at'=>null])->select(['id','name_abbreviation'])->get()->map(function ($item)  use ($data_list){
                        if(data_get($item, 'id')==$data_list->customer_suppliers_id)
                        {
                            global $name_abbreviation;
                            $name_abbreviation= data_get($item, 'name_abbreviation');
                        }

                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name_abbreviation');
                        return $return;
               });
              //获取开票类型税率
              global $invoice_name,$tax_rate;
              $invoice_type_arr=InvoiceType::query()->select(['id','name','tax_rate'])->get()->map(function ($item) use ($data_list){
                        if(data_get($item, 'id')==$data_list->invoice_types_id)
                        {
                            global $invoice_name,$tax_rate;
                            $invoice_name= data_get($item, 'name');
                            $tax_rate= data_get($item, 'tax_rate');
                        }
                        $return = [];
                        $return['id'] = data_get($item, 'id');
                        $return['name'] = data_get($item, 'name');
                        $return['tax_rate'] = data_get($item, 'tax_rate');
                        return $return;
               });


            $data= [

                'id' => $data_list->id,
                'user_name' => $data_list->users->name ?? null,
                'updated_at' => (string)$data_list->updated_at,
                'charge_ltem_id' => $data_list->charge_item_id,
                'segment_business_id' => $data_list->segment_business_id,
                'segment_business_name' =>$segment_businesses_name ?? null,
                'segment_business_list' => $segment_business_list,
                'master_business_id' => $data_list->master_business_id,
                'master_business_name' =>$master_businesses_name ?? null,
                'master_business_list' =>$master_business_list,
                'slaver_business_id' => $data_list->slaver_business_id,
                'slaver_business_name' =>$slaver_businesses_name ?? null,
                'slaver_business_list' => $slaver_business_list,
                'customer_suppliers_id' => $data_list->customer_suppliers_id ?? null,
                'name_abbreviation' => $name_abbreviation ?? null,
                'customer_supplier_list' => $customer_supplier_arr,
                'invoice_types_id' => $data_list->invoice_types_id ?? null,
                'invoice_name' => $invoice_name ?? null,
                'tax_rate' => $tax_rate ?? 0,
                'invoice_type_list' => $invoice_type_arr,
                'is_tax_free'=>$data_list->is_tax_free,


            ];
            return $data;
        });

        return $collection;
    }

    /**
     * 更新01907
     * @queryParam id required id
     * @queryParam segment_business_id required 业务板块id
     * @queryParam master_business_id required 主业务板块
     * @queryParam slaver_business_id required 子业务板块
     * @queryParam charge_item_id required 费用科目id
     * @queryParam customer_suppliers_id required 结算公司(客户供应商)id
     * @queryParam invoice_types_id required 开票类型id
     * @queryParam is_tax_free required 是否免税 0：否，1：是
     * @response {
     * }
     * @param Request $request
     * @param $id
     * @return array
     */
    public function detailUpdate(Request $request,$id)
    {

            //获取该费用科目下的数据
            $tax_rate_arr = ChargeItemTaxRate::query()->where("charge_item_id",$id)->select(['id'])->get()->toArray();

            $delete_id_arr=array();
            $request_arr=$request->all();
            foreach ($request_arr as $value) {
                $delete_id_arr[]= $value['id'];
                //修改数据使用实例
                $ChargeItemTaxRate = ChargeItemTaxRate::find($value['id']);
                if(empty($ChargeItemTaxRate)){
                    //新增数据使用实例
                    $ChargeItemTaxRate = new ChargeItemTaxRate();
                }
                $ChargeItemTaxRate->charge_item_id = $id;
                $ChargeItemTaxRate->segment_business_id = $value['segment_business_id'];
                $ChargeItemTaxRate->master_business_id = $value['master_business_id'];
                $ChargeItemTaxRate->slaver_business_id = $value['slaver_business_id'];
                $ChargeItemTaxRate->customer_suppliers_id = $value['customer_suppliers_id'];
                $ChargeItemTaxRate->invoice_types_id = $value['invoice_types_id'];
                $ChargeItemTaxRate->is_tax_free = $value['is_tax_free'];
                $ChargeItemTaxRate->user_id = $request->get('login_user_id');
                $ChargeItemTaxRate->save();
            }
             $tax_rate_arr=array_pluck($tax_rate_arr,'id');
             foreach ($tax_rate_arr as $value1) {
                //删除charge_item_id关联数据
                if(!in_array($value1, $delete_id_arr)){
                    ChargeItemTaxRate::query()->where("id",$value1)->delete();
                }
             }

        return [];
    }


}
