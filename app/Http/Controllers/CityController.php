<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group 行政区023
 */
class CityController extends Controller
{
    /**
     * 行政区 02301
     * @jsonParam parent_id int required 父节点，例如为0时，获取所有国家,然后以获取到的id为参数，会获取到该国家下的省份 Example:0
     * @response{
     *  "id":1,
     *  "parent_id":0,
     *  "name":"中国"
     * }
     * @param Request $request
     * @return City[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request)
    {
        $messages = [
            "parent_id.integer" => 'id必须是整数'
        ];
        $rules = [
            'parent_id' => 'integer'
        ];
        $this->validate($request, $rules, $messages);

        $parent_id = $request->input('parent_id');
        $city = City::query()->where("parent_id", $parent_id)->get();
        return $city;
    }

    /**
     * 国家城市新增、更新 02302
     * 修改国家或城市，必有有相应的id,新增城市，需要有country_id
     *
     * @bodyParam country required 国家
     * @bodyParam country_id required 国家id
     * @bodyParam city required 城市
     * @bodyParam city_id required 城市id
     * @response{
     *      "country":{
     *          "id":11,
     *          "parent_id":0,
     *          "name":"中国",
     *          "type":0
     *      },
     *      "city":{
     *          "id":12,
     *          "parent_id":11,
     *          "name":"太仓",
     *          "type":1
     *      }
     * }
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    function update(Request $request)
    {
        //<editor-fold desc="数据校验">
        $messages = [
        ];
        $rules = [
            "country_id" => ["integer", Rule::exists("cities", "id")->where('parent_id', 0)],
            "city_id" => ["integer", Rule::exists('cities', 'id')->whereNot('parent_id', 0)],
            "country" => [
                "between:1,50",
                "unique:cities,name,{$request->input('country_id')},id",
                Rule::requiredIf(function () use ($request) {
                    if ($request->input('country_id')) {
                        return true;
                    }
                    return false;
                })],
            "city" => [
                "between:1,50",
                "unique:cities,name,{$request->input('city_id')},id",
                Rule::requiredIf(function () use ($request) {
                    if ($request->input('city_id')) {
                        return true;
                    }
                    return false;
                })
            ],
        ];

        \Illuminate\Support\Facades\Validator::extend('country_id_check', function ($attribute, $value) use ($request) {
            if ($attribute !== 'country_id_check') {
                return false;
            }
            $static = City::query()->where('id', $value)->first();
            if ($static->parent_id != $request->input('country_id')) {
                return false;
            }
            return true;
        });

        $this->validate($request, $rules, $messages);
        //</editor-fold>

        $t = new City();
        $country = clone $t;
        $city = clone $t;
        //<editor-fold desc="新增国家和城市">
        if (empty($request->input('country_id')) && empty($request->input('city_id'))) {
            \DB::transaction(function () use ($request, &$country, &$city) {

                $country->name = $request->input('country');
                $country->type = 0;
                $country->parent_id = 0;
                $country->save();

                $city->name = $request->input('city');
                $city->type = 1;
                $city->parent_id = $country->id;
                $city->save();
            });
        }
        //</editor-fold>

        //<editor-fold desc="新增城市">
        if ($request->input('country_id') && empty($request->input('city_id'))) {
            \DB::transaction(function () use ($request, &$country, &$city) {
                $country = $country::query()->where("id", $request->input('country_id'))->first();
                $request->has('country') && $country->name = $request->input('country');
                $country->save();

                $city->name = $request->input('city');
                $city->type = 1;
                $city->parent_id = $request->input('country_id');
                $city->save();
            });
        }
        //</editor-fold>

        //<editor-fold desc="修改国家和城市">
        if ($request->input('country_id') && $request->input('city_id')) {
            \DB::transaction(function () use ($request, &$country, &$city) {
                $country = $country->where("id", $request->input('country_id'))->first();
                $request->input('country') && $country->name = $request->input('country');
                $country->parent_id = 0;
                $country->type = 0;
                $country->save();

                $city = $city->where("id", $request->input('city_id'))->first();
                $request->input('city') && $city->name = $request->input('city');
                $request->input('parent_id') && $city->parent_id = $request->input('parent_id');
                $city->type = 1;
                $city->save();
            });
        }
        //</editor-fold>
        return ['country' => $country, 'city' => $city];
    }

}
