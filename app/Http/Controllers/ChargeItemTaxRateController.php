<?php

namespace App\Http\Controllers;

use App\Business;
use App\ChargeItemTaxRate;
use function foo\func;
use Illuminate\Http\Request;

/**
 * @group 费用科目与税率关系维护020
 */
class ChargeItemTaxRateController extends Controller
{
    /**
     * 列表02001
     *
     * @queryParam page 第几页，默认为1
     * @queryParam per_page 每页数，默认为10
     * @queryParam search 搜索
     * @response {
     * "data":[{
     *  "id": 4,
     *  "segment_name": "业务板块名称",
     *  "master_name": "主业务类型名称",
     *  "slaver_name": "子业务类型名称",
     *  "charge_items_name": "费用科目名称",
     *  "charge_items_code": "费用科目代码",
     *  "invoice_types_name": "开票类型",
     *  "invoice_types_tax": "开票费率",
     *  "is_tax_free":"0-不免税1-免税"
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
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $list = ChargeItemTaxRate::query()->with(['segment_businesses','master_businesses','slaver_businesses','charge_items','invoice_types'])
                                          ->whereHas('charge_items',function($q)use($search){
                                            /** @type \Illuminate\Database\Eloquent\Builder $q */
                                            $q->when($search,function ($q,$search){
                                                /** @type \Illuminate\Database\Eloquent\Builder $q */
                                                return $q->where('code','like',"%{$search}%");
                                            });
                                        })->orderBy("updated_at","desc")->paginate($request->get('per_page',10));
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
//        $collection = $list->getCollection()->map(function ($list){
//            /** @var ChargeItemTaxRate $list */
//            $data = [
//                        'id'=>$list->id,
//                        'segment_name'=>$list->segment_businesses->name ?? '',
//                        'master_name' =>$list->master_businesses->name ?? '',
//                        'slaver_name' => $list->slaver_businesses->name ?? '',
//                        'charge_items_name' => $list->charge_items->name ?? '',
//                        'charge_items_code' => $list->charge_items->code ?? '',
//                        'invoice_types_name' => $list->invoice_types->name ?? '',
//                        'invoice_type_tax'   => $list->invoice_types->tax_rate ?? '',
//                        'is_tax_free'=>$list->is_tax_free
//                    ];
//            return $data;
//        });

        $collection = $list->setCollection($list->getCollection()->map(function ($list){
            /** @var ChargeItemTaxRate $list */
            $data = [
                'id'=>$list->id,
                'segment_name'=>$list->segment_businesses->name ?? '',
                'master_name' =>$list->master_businesses->name ?? '',
                'slaver_name' => $list->slaver_businesses->name ?? '',
                'charge_items_name' => $list->charge_items->name ?? '',
                'charge_items_code' => $list->charge_items->code ?? '',
                'invoice_types_name' => $list->invoice_types->name ?? '',
                'invoice_type_tax'   => $list->invoice_types->tax_rate ?? '',
                'is_tax_free'=>$list->is_tax_free
            ];
            return $data;
        }));
        return $collection;

        $list = ChargeItemTaxRate::query()->with(['segment_businesses'=>function($q)use($search){
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->when($search,function ($q,$search){
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                return $q->where('name','like',"%{$search}%");
            });
        }])->has('segment_businesses')->orderBy("updated_at","desc")->paginate($request->get('per_page',100));;
        return $list;
//        (new ChargeItemTaxRate())->newQuery()->whereHas('segment_businesses_id');
//        echo $request->input('xxx');
//die();
//        $tmp = ChargeItemTaxRate::with('segment_businesses:name,id')->has('segment_businesses')->orderBy("updated_at","desc")->paginate($request->get('per_page',10));;
//        return $tmp;


//        ChargeItemTaxRate::with()
//        $chargeItemTaxRate = ChargeItemTaxRate::query()->with(['segment_businesses'=>function($q)use($request){
//            /** @type \Illuminate\Database\Eloquent\Builder $q */
////            $segment_name = $request->input('search');
////            $q->whereHas("name","like","%{$segment_name}%");
//        },'master_businesses','slaver_businesses','charge_items','invoice_types'])->has('invoice_types')->has('charge_items')
////            ->whereNotNull("")
//            ->orderBy("updated_at","desc")->paginate($request->get('per_page',10));
//        return $chargeItemTaxRate;
//        $user_id = 1;
//        $contract = Contract::query()->with(['contract_reviews'=>function($q){
//            /** @type \Illuminate\Database\Eloquent\Builder $q */
//            $q->with(['users'])->where('id',1);
//        }])->orderBy("updated_at","desc")->paginate($request->get('per_page',10));
//        $anonymousResourceCollection = \App\Http\Resources\Contract::collection($contract);
////        $anonymousResourceCollection = \App\Http\Resources\Business::collection($business);
////        return $contract;
//        return $anonymousResourceCollection;
    }

    /**
     * 插入02002
     *
     * @bodyParam slaver_businesses_id int required 子业务类型id
     * @bodyParam charge_item_id int required 费用科目id
     * @bodyParam invoice_types_id int required 开票类型id
     * @bodyParam is_tax_free int required 是否免税
     * @response {
     *  "id": 4,
     *  "segment_businesses_id": "业务板块id",
     *  "master_businesses_id":"主业务类型id",
     *  "slaver_businesses_id":"子业务类型id",
     *  "charge_items_id":"费用科目id",
     *  "invoice_types_id":"开票类型id",
     *  "is_tax_free":"是否免税",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     */
    public function store(Request $request)
    {
        $slaver_businesses_id = $request->input('slaver_businesses_id');
        $master_businesses_id  = Business::query()->where('id', $slaver_businesses_id)->value('id');
        $segment_businesses_id = Business::query()->where('id',$master_businesses_id)->value('id');

        $chargeItemTaxRate = new ChargeItemTaxRate();
        $chargeItemTaxRate->segment_businesses_id = $segment_businesses_id;
        $chargeItemTaxRate->master_businesses_id = $master_businesses_id;
        $chargeItemTaxRate->slaver_businesses_id = $slaver_businesses_id;
        $chargeItemTaxRate->charge_items_id = $request->input('charge_items_id');
        $chargeItemTaxRate->invoice_types_id = $request->input('invoice_types_id');
        $chargeItemTaxRate->is_tax_free = $request->input('is_tax_free');
        $chargeItemTaxRate->save();

        return $chargeItemTaxRate->toArray();
    }

    /**
     * 显示02003
     * @queryParam chargeItemTaxRate required 费用科目与税率关系表id
     * @response {
     *  "id": 4,
     *  "segment_businesses_id": "业务板块id",
     *  "segment_businesses_name":"业务板块名称",
     *  "master_businesses_id":"主业务类型id",
     *  "master_businesses_name":"主业务类型名称",
     *  "slaver_businesses_id":"子业务类型id",
     *  "slaver_businesses_name":"子业务类型名称",
     *  "charge_items_id":"费用科目id",
     *  "charge_items_code":"费用科目代码",
     *  "charge_items_name":"费用科目名称",
     *  "invoice_types_id":"开票类型id",
     *  "invoice_types_name":"开票类型名称",
     *  "invoice_types_tax":"开票类型税率",
     *  "is_tax_free":"是否免税",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     * }
     */
    public function show(ChargeItemTaxRate $chargeItemTaxRate)
    {
        $result = ChargeItemTaxRate::query()->with([
                                                'segment_businesses',
                                                'master_businesses',
                                                'slaver_businesses',
                                                'charge_items',
                                                'invoice_types'
                                            ])->where('id',$chargeItemTaxRate->id)->get();
        $return = collect($result)->map(function ($result,$key){
            /** @var ChargeItemTaxRate $result */
            $data = [
                'id'=>$result->id,
                'segment_business_id' => $result->segment_businesses->id ?? "",
                'segment_buisness_name'=>$result->segment_businesses->name ?? "",
                'master_business_id' => $result->master_businesses->id ?? "",
                'master_buisness_name'=>$result->master_businesses->name ?? "",
                'slaver_business_id' => $result->slaver_businesses->id ?? "",
                'slaver_buisness_name'=>$result->slaver_businesses->name ?? "",
                'charge_items_id' =>$result->charge_items->id ?? "",
                'charge_items_code'  =>$result->charge_items->code ?? "",
                'charge_items_name'  =>$result->charge_items->name ?? "",
                'invoice_types_id'  =>$result->invoice_types->id ?? "",
                'invoice_types_name'  =>$result->invoice_types->name ?? "",
                'invoice_types_tax'  =>$result->invoice_types->tax_rate ?? "",
                'is_tax_free' => $result->is_tax_free ?? "",
                'created_at'=>(string)$result->created_at ?? "",
                'updated_at'=>(string)$result->updated_at ?? "",
            ];
            return $data;
        });

        return $return;
    }

    /**
     * 更新02004
     *
     * @queryParam chargeItemTaxTate required 费用科目与税率关系维护id
     * @queryParam slave_businesses_id required 子业务类型id
     * @queryParam charge_items_id required 费用科目名称
     * @queryParam invoice_types_id required 开票类型id
     * @queryParam is_tax_free 是否免税 0-不免1-免
     * @response 200{
     * }
     */
    public function update(Request $request, ChargeItemTaxRate $chargeItemTaxRate)
    {
        if($request->get('slave_businesses_id')){
            $chargeItemTaxRate->slaver_businesses_id = $request->get('slave_businesses_id');
            $chargeItemTaxRate->master_businesses_id = Business::query()->where("id",$request->get("slave_businesses_id"))->value('parent_id');
            $chargeItemTaxRate->segment_businesses_id = Business::query()->where("id",$chargeItemTaxRate->master_businesses_id)->value('parent_id');
        }
        if($request->get('charge_items_id')){
            $chargeItemTaxRate->charge_items_id = $request->get('charge_items_id');
        }
        if($request->get('invoice_types_id')){
            $chargeItemTaxRate->invoice_types_id  = $request->get('invoice_types_id');
        }
        if($request->has('is_tax_free') && $request->get('is_tax_free')){
            $chargeItemTaxRate->is_tax_free = $request->get('is_tax_free');
        }
        $chargeItemTaxRate->save();
        return $chargeItemTaxRate;
    }

    /**
     * 删除02005
     *
     * @queryParam chargeItemTaxRate required 费用科目与税率关系维护id
     * @response 200{
     * }
     **/
    public function destroy(ChargeItemTaxRate $chargeItemTaxRate)
    {
        ChargeItemTaxRate::destroy($chargeItemTaxRate->id);

        return $chargeItemTaxRate->toArray();
    }
}
