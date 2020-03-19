<?php

namespace App\Http\Controllers;

use App\CustomerSupplier;
use App\CustomerSupplierShipData;
use App\ShipCompany;
use Illuminate\Http\Request;
/**
 * @group 【废弃】船公司006
 */
class ShipCompanyController extends Controller
{
    /**
     * 列表00601
     *
     * @queryParam page int 第几页，默认第一页
     * @queryParam per_page int 每页记录数，默认是10
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "船公司名称",
     *  "status":"0-禁止1-启用",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "ship":[{
     *    "id": 4,
     *    "name": "船名称",
     *    "status":"0-禁止1-启用",
     *    "route":[{
     *       "id": 4,
     *       "name": "航线名称",
     *       "status":"0-禁止1-启用"
     *          }]
     *      }]
     * }],
     *  "current_page": 1,
     *  "first_page_url": "http://host/api/v1/shipCompanies?page=1",
     *  "from": 1,
     *  "last_page": 5,
     *  "last_page_url": "http://host/api/v1/shipCompanies?page=5",
     *  "next_page_url": "http://host/api/v1/shipCompanies?page=2",
     *  "path": "http://host/api/v1/shipCompanies",
     *  "per_page": 10,
     *  "prev_page_url": null,
     *  "to": 10,
     *  "total": 50
     * }
     * @param Request $request
     * @return \Illuminate\Pagination\AbstractPaginator
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $key = array_search('船公司',\Config::get('constants.LOGISTICS_ROLE'));
        if($key === false){
            throw new \Exception('请在constants.php文件内定义船公司常量');
        }
//        return $this->hasMany(CustomerSupplierShipData::class);//->whereRaw("find_in_set({$key},logistics_role)");
        $list = CustomerSupplier::query()->with(['customer_supplier_ship_data'=>function($q){
            /** @type \Illuminate\Database\Eloquent\Builder $q */
            $q->with(['customer_supplier_ship_data']);
        }])
            ->whereRaw("find_in_set({$key},logistics_role)")
                                        ->orderBy("updated_at","desc")
                                        ->paginate($request->get('per_page',10));
        /** @var \Illuminate\Pagination\AbstractPaginator $list */
        $collection = $list->setCollection($list->getCollection()->map(function ($list){
            /** @var CustomerSupplier $list */
            $data = [
                'id'=>data_get($list,'id'),
                'name'=>data_get($list,'name'),
                'status' =>data_get($list,'status'),
                'created_at'=>(string)data_get($list,'created_at',null),
                'updated_at'=>(string)data_get($list,'updated_at',null),
                'ship'=>collect($list->customer_supplier_ship_data)->map(function($item/*,$key*/){
                    /** @var CustomerSupplierShipData $item*/
                    $data = [
                        'id'=>$item->id,
                        'name'=>$item->name,
                        'status'=>$item->status,
                        'route'=>collect($item->customer_supplier_ship_data)->map(function($item/*,$key*/){
                            /** @var CustomerSupplierShipData $item*/
                            $data = [
                                'id'=>$item->id,
                                'name'=>$item->name,
                                'status'=>$item->status,
                            ];
                            return $data;
                        }),
                    ];
                    return $data;
                }),
            ];
            return $data;
        }));
        return $collection;
    }

    /**
     * 插入00602
     *
     * @bodyParam parent_id int 父节点id，默认为0
     * @bodyParam name string required 航线名称
     * @response {
     * }
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $shipCompany = new ShipCompany();
        $request->has('parent_id') && $request->input('parent_id') && $shipCompany->parent_id = $request->input('parent_id',0);
        $shipCompany->name = $request->input('name');
        $shipCompany->save();
        return [];
    }

    /**
     * 【废弃】详情00603
     *
     * @queryParam shipCompany int 船公司id
     * @response {
     *  "id": 4,
     *  "parent_id":0,
     *  "name": "船公司名称/船名/航次",
     *  "status":0,
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @param ShipCompany $shipCompany
     * @return ShipCompany
     */
    public function show(ShipCompany $shipCompany)
    {
        return $shipCompany;
    }

    /**
     * 更新(船和航线,公司更新在客户供应商接口)00604
     * @queryParam shipCompany required int 船公司id
     * @queryParam parent_id int 船公司父id
     * @queryParam name  string名称
     * @queryParam status int 状态0-禁止1-启用
     * @response {
     * }
     * @param Request $request
     * @param ShipCompany $shipCompany
     * @return array
     */
    public function update(Request $request, ShipCompany $shipCompany)
    {
        $request->has('parent_id') && $request->input('parent_id') && $shipCompany->parent_id = $request->input('parent_id');
        $request->has('name') && $request->input('name') && $shipCompany->name = $request->input('name');
        $request->has('status') && $shipCompany->status = $request->input('status');
        $shipCompany->save();
        return [];
    }

    /**
     * 删除00605
     * @queryParam shipCompany required 航id\航次id
     * @response {
     * }
     * @param ShipCompany $shipCompany
     * @return array
     */
    public function destroy(ShipCompany $shipCompany)
    {
        ShipCompany::destroy($shipCompany->id);
        return [];
//        return $shipCompany->refresh();
    }
}
