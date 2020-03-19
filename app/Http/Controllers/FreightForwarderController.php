<?php

namespace App\Http\Controllers;

use App\FreightForwarder;
use App\Http\Resources\JjdoorCollection;
use App\Http\Resources\XxxCollection;
use Illuminate\Http\Request;

/**
 * @group 货运代理026
 */
class FreightForwarderController extends Controller
{
    /**
     * 列表02601
     * @queryParam page int 第几页，默认为1
     * @queryParam per_page int 每页数，默认为10
     * @queryParam search string 公司/个人(全程/简称/助记码)模糊搜索
     * @queryParam segment_business_id int 业务板块id
     * @queryParam master_business_id int 主业务类型id
     * @queryParam slaver_business_id int 子业务类型id
     * @queryParam created_at string 委托日期
     * @queryParam contract_sn string 合同编号
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $search = $request->input('search');
//        $segment_business_id = $request->input('segment_business_id');
//        $master_business_id = $request->input('master_business_id');
//        $slaver_business_id = $request->input('slaver_business_id');
//        $created_at = $request->input('created_at');
//        $contract_sn = $request->input('contract_sn');

        $list = FreightForwarder::query()->with([
            'segment_businesses',
            'master_businesses',
            'slaver_businesses',
            'customer_suppliers',
            'clear_companies',
            'contracts'
        ])
            ->with(['last_process_logs' => function ($q) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('status', 1);
            }])
            ->leftJoin('customer_suppliers', 'freight_forwarders.customer_supplier_id', '=', 'customer_suppliers.id')
            ->leftJoin('clear_companies', 'freight_forwarders.clear_company_id', '=', 'clear_companies.id')
            ->when($request->has('search'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where(function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where(function ($q) use ($request) {
                        /** @type \Illuminate\Database\Eloquent\Builder $q */
                        $q->where('customer_suppliers.name', 'like', "%{$request->input('search')}%")
                            ->orWhere('customer_suppliers.name_abbreviation', 'like', "%{$request->input('search')}%")
                            ->orWhere('customer_suppliers.name_code', 'like', "%{$request->input('search')}%");
                    });
                    $q->orWhere('clear_companies.name', 'like', "%{$request->input('search')}%");
                });
            })
            ->when($request->has('segment_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('segment_business_id', $request->input('segment_business_id'));
            })
            ->when($request->has('master_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('master_business_id', $request->input('master_business_id'));
            })
            ->when($request->has('slaver_business_id'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('slaver_business_id', $request->input('slaver_business_id'));
            })
            ->when($request->has('created_at'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->where('freight_forwarders.created_at', $request->input('created_at'));
            })
            ->when($request->has('contract_sn'), function ($q) use ($request) {
                /** @type \Illuminate\Database\Eloquent\Builder $q */
                $q->whereHas('contracts', function ($q) use ($request) {
                    /** @type \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('sn', $request->input('contract_sn'));
                });
            })
//            ->has('contracts','>=',1,'and',function($q)use($request){
//                /** @type \Illuminate\Database\Eloquent\Builder $q */
//                $q->where('sn',$request->input('contract_sn'));
//            })
//            ->where('freight_forwarders.id','in',function ($q){
            /** @type \Illuminate\Database\Eloquent\Builder $q */

//            })
//            ->whereIn('contract_freight_forwarder.contract_id',[1,2,3,4,5])
//            ->whereIn('contract_id',function ($q)use($request){
            /** @type \Illuminate\Database\Eloquent\Builder $q */
//                $q->select('id')->where('sn',$request->input('contract_sn'))->take(3);
//                return $return;
//                echo "<Pre>";
//                var_dump($return);die();
//            })
            ->orderBy("freight_forwarders.updated_at", "desc")->paginate($request->get('per_page', 10));
//            $a = 1;
//            $b = 23;
//            $c = $b;
//            return XxxCollection::collection($list);
//        return $list;

        $list = $list->setCollection($list->getCollection())->map(function ($list) {
            /** @var FreightForwarder $list */
            $data = $list->toArray();
            $data['contracts'] = FreightForwarder::query()->with(['contracts'])->where('id', $list->id)->get();

            $data = [];
            $data['id'] = $list->id;
            $data['process'] = data_get($list, 'last_process_logs.name', null);
            $data['segment_businesses_name'] = data_get($list, 'segment_businesses.name', null);
            $data['master_businesses_name'] = data_get($list, 'mastger_businesses.name', null);
            $data['slaver_businesses_name'] = data_get($list, 'slaver_businesses.name', null);
//            $data['']
            return $data;
        });

        return $list;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FreightForwarder $freightForwarder
     * @return \Illuminate\Http\Response
     */
    public function show(FreightForwarder $freightForwarder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\FreightForwarder $freightForwarder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreightForwarder $freightForwarder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FreightForwarder $freightForwarder
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreightForwarder $freightForwarder)
    {
        //
    }
}
