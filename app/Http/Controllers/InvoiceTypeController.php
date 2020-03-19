<?php

namespace App\Http\Controllers;

use App\InvoiceType;
use Illuminate\Http\Request;

/**
 * @group 开票类型027
 */
class InvoiceTypeController extends Controller
{
    /**
     * 列表02701
     *
     * @queryParam page int第几页，默认为1
     * @queryParam per_page int每页数，默认为10
     * @queryParam direction int收付标志 1：收，2：付，3：收付
     * @queryParam name varchar 开票类型
     * @queryParam tax_rate int 税率
     * @response {
     * "data":[{
     *  "id": 4,
     *  "direction": "收付标志 1：收，2：付，3：收付",
     *  "name": "开票类型",
     *  "tax_rate": "税率",
     *  "user_name": "录入人",
     *  "status": "状态 0禁用，1启用",
     *  "created_at": "创立时间",
     *  "updated_at": "更新时间"
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/invoiceTypes?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/invoiceTypes?page=5",
     *  "next_page_url": "http://host/api/v1/invoiceTypes?page=2",
     *  "path": "http://host/api/v1/invoiceTypes",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        //关联users表
        $list = InvoiceType::query()->with(['users'])
          //查询条件
          ->when($request->input('name') !== null, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('name')}%");
           })
          ->when($request->input('tax_rate') !== null, function ($q) use ($request) {
                $q->where('tax_rate', 'like', "%{$request->input('tax_rate')}%");
           })
          ->when($request->input('direction') !== null, function ($q) use ($request) {
                $q->where('direction',$request->input('direction'));
           })
         ->orderBy("updated_at","desc")->paginate($request->get('per_page',10));

        $collection = $list->setCollection($list->getCollection()->map(function ($list){

            $data = [
                'id'=>$list->id,
                'direction'=>data_get($list,'direction',null),
                'name' =>data_get($list,'name',null),
                'tax_rate' => data_get($list,'tax_rate',null),
                'user_name' => data_get($list,'users.name'),
                'status' => data_get($list,'status'),
                'created_at' => (string)data_get($list,'created_at'),
                'updated_at' => (string)data_get($list,'updated_at'),
            ];
            return $data;
        }))->toArray();
        // 1：收，2：付，3：收付
         $direction_arr = [['key'=>1,'value'=>'收'],['key'=>2,'value'=>'付'],['key'=>3,'value'=>'收付']];
        //获取表中所有数据
        $data_all = InvoiceType::query()->select('id','name','tax_rate')->get();
        //去重税率
        $InvoiceTaxRate= $data_all->pluck('tax_rate');
        $tax_rate_arr=$InvoiceTaxRate->all();

        $invoice_arr=array();
        foreach ($data_all->toArray() as $value){
            $value1['key']=$value['id'];
            $value1['value']=$value['name'];
            $invoice_arr[]=$value1;
        }
        return array_merge($collection, ['name' => $invoice_arr],['direction' => $direction_arr],['tax_rate' => $tax_rate_arr]);
    }

    /**
     * 插入02702
     *
     * @bodyParam direction int required 收付标志 1：收，2：付，3：收付
     * @bodyParam name string required 开票类型
     * @bodyParam tax_rate int required 税率
     * @bodyParam status int required 状态 0：禁用，1：启用
     * @response {
     * }
     * @param Request $request
     * @return array
     */
    public function invoiceAdd(Request $request)
    {

        $this->validate($request, [
            'direction' => 'between:1,80|required',
            'name' => 'between:1,30|required',
            'tax_rate' => 'between:1,20|required',
            'status' => 'boolean|required',
        ]);
        $invoiceType = new InvoiceType();
        $invoiceType->name = $request->input('name');
        $invoiceType->direction = $request->input('direction');
        $invoiceType->tax_rate = $request->input('tax_rate');
        $invoiceType->status = $request->input('status');
        $invoiceType->user_id = $request->get('login_user_id');
        $invoiceType->save();

        return [];
    }

   /**
     * 状态更新02703
     *
     * @queryParam ids required 多个用英文逗号分割
     * @response 200{
     * }
     * @param InvoiceType $invoiceType
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
            if (InvoiceType::query()->whereIn("id", $explode)->count() === count($explode)) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);
        //批量修改状态status
        $ids_arr = explode(",", $request->input('ids'));
        InvoiceType::query()->whereIn("id", $ids_arr)->update(['status'=> $request->input('status')]);
        return [];
    }

    /**
     * 更新02704
     *
     * @queryParam id required int开票类型id
     * @queryParam direction int 收付标志 1：收，2：付，3：收付
     * @queryParam name string 开票类型
     * @queryParam tax_rate int税率
     * @bodyParam status int required 状态 0：禁用，1：启用
     * @response 200{
     * }
     * @param Request $request
     * @param InvoiceType $invoiceType
     * @return array
     */
    public function invoiceUpdate(Request $request)
    {
        $this->validate($request, [
            'direction' => 'between:1,80|required',
            'name' => 'between:1,30|required',
            'tax_rate' => 'between:1,20|required',
            'status' => 'boolean|required',
        ]);

        $InvoiceType = InvoiceType::find($request->input('id'));
        $InvoiceType->direction = $request->input('direction');
        $InvoiceType->name = $request->input('name');
        $InvoiceType->tax_rate = $request->input('tax_rate');
        $InvoiceType->status = $request->input('status');
        $InvoiceType->user_id = $request->get('login_user_id');
        $InvoiceType->save();
        return [];
    }


    /**
     * 删除02705
     *
     * @queryParam ids required 多个用英文逗号分割
     * @response 200{
     * }
     * @param InvoiceType $invoiceType
     * @return array
     */
    public function destroy(Request $request)
    {

        $ids=$request->input('ids');
        $ids_arr=explode(",",$ids);
        foreach ($ids_arr as $value) {

              InvoiceType::destroy($value);

           }
        return [];
    }
}
