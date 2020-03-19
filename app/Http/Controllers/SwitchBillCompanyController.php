<?php

namespace App\Http\Controllers;

use App\SwitchBillCompany;
use Illuminate\Http\Request;

/**
 * @group 【废弃】换单公司007
 */
class SwitchBillCompanyController extends Controller
{
    /**
     * 列表00701
     *
     * @queryParam page 第几页，默认第一页
     * @queryParam per_page 每页记录数，默认是10
     * @response {
     * "data":[{
     *  "id": 4,
     *  "name": "换单公司",
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
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //validate test
        $validator = validator()->validate($request->toArray(),
            [
                'code' => 'required|mobile',
                'title' => 'required',
            ], [
                'code.required' => 'The :attribute is invalid .',
                'title.required' => '1:attribute1',
            ], [
                'code' => 'code of captcha',
                'title' => '哈哈标题哈哈'
            ]
        );
        echo "<pre>";
        print_r($validator);
        die();
        return $validator;

        $request->validate([
//            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        die();
        $this->validator($request, [
            'password' => 'required|string',
        ]);
        $this->validate($request, [
            'per_page' => 'required|unique:posts|max:255',
            'page' => 'required'
        ]);
        die("==");
        $list = SwitchBillCompany::query()->orderBy("updated_at", "desc")->paginate($request->get('per_page', 10));
        $collection = $list->setCollection($list->getCollection()->map(function ($list) {
            /** @var SwitchBillCompany $list */
            $data = [
                'id' => $list->id,
                'name' => $list->name ?? '',
                'status' => $list->status ?? '',
                'created_at' => (string)data_get($list, 'created_at', null),
                'updated_at' => (string)data_get($list, 'updated_at', null),
            ];
            return $data;
        }));
        return $collection;

//        return \App\Http\Resources\SwitchBillCompany::collection($business);
    }

//    protected function validator11(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//    }

    /**
     * 插入00702
     *
     * @bodyParam name string required 换单公司名称
     * @response {
     *  "id": 4,
     *  "name": "换单公司名称",
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间",
     *  "status":"0-禁止1-启用"
     * }
     * @param Request $request
     * @return SwitchBillCompany
     */
    public function store(Request $request)
    {
        $switchBillCompany = new SwitchBillCompany();
        $switchBillCompany->name = $request->input('name');
        $switchBillCompany->save();
        return $switchBillCompany;
    }

    /**
     * 详情00703
     *
     * @queryParam switchBillCompany required 换单公司id
     * @response {
     *  "id": 4,
     *  "name": "换单公司名称",
     *  "status":0,
     *  "created_at": "生成时间",
     *  "updated_at": "修改时间"
     * }
     * @param SwitchBillCompany $switchBillCompany
     * @return SwitchBillCompany
     */
    public function show(SwitchBillCompany $switchBillCompany)
    {
        return $switchBillCompany;
    }

    /**
     * 更新00704
     * @queryParam switchBillCompany required 换单公司id
     * @queryParam name 换单公司名称
     * @queryParam status 状态0-禁止1-启用
     * @response {
     * "id":1,
     * "name":"换单公司名称",
     * "parent_id":0,
     * "created_at":"2019-06-12 07:48:10",
     * "updated_at":"2019-06-12 07:48:10"
     * }
     * @param Request $request
     * @param SwitchBillCompany $switchBillCompany
     * @return SwitchBillCompany
     */
    public function update(Request $request, SwitchBillCompany $switchBillCompany)
    {
        $switchBillCompany->id = $request->input('id');
        if ($request->input('name') !== null) {
            $switchBillCompany->name = $request->input('name');
        }
        if ($request->input('status') !== null) {
            $switchBillCompany->status = $request->input('status');
        }
        $switchBillCompany->save();
        return $switchBillCompany;
    }

    /**
     * 删除00705
     * @queryParam port required 换单公司id
     * @response {
     * "id":1,
     * "name":"换单公司名称",
     * "parent_id":0,
     * "created_at":"2019-06-12 07:48:10",
     * "updated_at":"2019-06-12 07:48:10"
     * }
     * @param SwitchBillCompany $switchBillCompany
     * @return SwitchBillCompany
     */
    public function destroy(SwitchBillCompany $switchBillCompany)
    {
        SwitchBillCompany::destroy($switchBillCompany->id);
        return $switchBillCompany->refresh();
    }
}
