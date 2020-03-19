<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-08-03
 * Time: 16:12
 */

namespace App\Service;

use App\Business;
use App\User;
use Illuminate\Database\Eloquent\Model;

trait BusinessService
{
    /**
     * 业务板块信息03008
     * @urlParam product required 品名id
     * @response {
     * }
     * @param Model $model
     * @return mixed
     */
    public function show_business_template(Model $model)
    {
        $product = $model::where("id", $model->id)->with(['users'])->with(['segment_businesses', 'master_businesses', 'slaver_businesses'])->first();
        $return = $product->segment_businesses->map(function ($item, $key) use ($product) {
            $master_business = $product->master_businesses;
            $slaver_business = $product->slaver_businesses;
            $return = [];
            $return['user_name'] = data_get(User::find(data_get($item, 'pivot.user_id')), 'name');
            $return['updated_at'] = (string)data_get($item, "pivot.updated_at");
            $return['segment_business_id'] = data_get($item, 'id');
            $return['segment_business_name'] = data_get($item, 'name');
            $return['segment_business_list'] = Business::query()->where("parent_id", 0)->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            $return['master_business_id'] = data_get($master_business[$key], 'id');
            $return['master_business_name'] = data_get($master_business[$key], 'name');
            $return['master_business_list'] = Business::query()->where("parent_id", $return['segment_business_id'])->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            $return['slaver_business_id'] = data_get($slaver_business[$key], 'id');
            $return['slaver_business_name'] = data_get($slaver_business[$key], 'name');
            $return['slaver_business_list'] = Business::query()->where("parent_id", $return['master_business_id'])->get()->map(function ($item) {
                $return = [];
                $return['id'] = data_get($item, 'id');
                $return['name'] = data_get($item, 'name');
                return $return;
            });
            return $return;
        });

        return $return;
    }

}