<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::group([],function(){
//    Route::get('company_organize.index',"CompanyOrganizeController@index");
//    Route::get('company_organize.show/{id}',"CompanyOrganizeController@show");
//    Route::post("company_organize.store","CompanyOrganizeController@store");
//    Route::put("company_organize.update/{id}","CompanyOrganizeController@update");
//    Route::delete("company_organize.destory/{id}","CompanyOrganizeController@destroy");
//});
//Route::group([],function(){
//    Route::get('clear_company.index',"ClearCompanyController@index");
//    Route::get('clear_company.show/{id}',"ClearCompanyController@show");
//    Route::post("clear_company.store","ClearCompanyController@store");
//    Route::put("clear_company.update/{id}","ClearCompanyController@update");
//    Route::delete("clear_company.destory/{id}","ClearCompanyController@destroy");
//});

//Route::group([],function(){
//    Route::get('business.index',"BusinessController@index");
//    Route::get('business.show/{id}',"BusinessController@show");
//    Route::post("business.store","BusinessController@store");
//    Route::put("business.update/{id}","BusinessController@update");
//    Route::delete("business.destory/{id}","BusinessController@destroy");
//});


//Route::get("post", "API\PostAPIController@index")->middleware(\App\Http\Middleware\Role::class);
Route::group(['prefix' => 'v1', 'middleware' => ['auth:api', 'role', 'ClearEmptyRequest']], function () {
    Route::apiResources([
//        'apiTokens' => 'ApiTokenController',
        'registers' => 'Auth\RegisterController',
        'businesses' => 'BusinessController',
//        'photos' => 'PhotoController',
//        'ports' => 'PortController',
        'companyOrganizes' => 'CompanyOrganizeController',
//        'clearCompanies' => 'ClearCompanyController',
        'shipCompanies' => 'ShipCompanyController',//不要
//        'routes' => 'RouteController',
        'switchBillCompanies' => 'SwitchBillCompanyController',//换单公司不要
//        'customerSuppliers' => "CustomerSupplierController",
        //'containerTypes' => 'ContainerTypeController',
        // 'containerAddresses' => 'ContainerAddressController',
        'freightForwarders' => 'FreightForwarderController',
        //'invoiceTypes' => 'InvoiceTypeController',
//        'ships' => 'ShipController',
    ]);
    //<editor-fold desc="公用接口">
    Route::post('common/upload', "CommonController@upload");
    Route::get('common/preview', "CommonController@preview");
    //</editor-fold>
    //<editor-fold desc="计算公司废弃">
    Route::post('clearCompanies/list', "ClearCompanyController@index");
    Route::post('clearCompanies/store', "ClearCompanyController@store");
    Route::post('clearCompanies/show/{clearCompany}', "ClearCompanyController@show");
    Route::post('clearCompanies/update/{clearCompany}', "ClearCompanyController@update");
    Route::post('clearCompanies/destroy/{clearCompany}', "ClearCompanyController@destroy");
    //</editor-fold>
    //<editor-fold desc="港口">
    Route::post('ports/list', "PortController@index");
    Route::post('ports/store', "PortController@store");
    Route::post('ports/updateOrInsert/{port}', "PortController@updateOrInsert");
//    Route::post('ports/show/{port}', "PortController@show");
    Route::post('ports/update/{port}', "PortController@update");
    Route::post('ports/destroy', "PortController@destroy");
    Route::post('ports/status', "PortController@status");
    Route::post('ports/search', "PortController@search");
    Route::post('ports/show/business/{port}', "PortController@show_business");
    //</editor-fold>
//<editor-fold desc="航线">
    Route::post('routes/list', "RouteController@index");
    Route::post('routes/store', "RouteController@store");
    Route::post('routes/updateOrInsert/{route}', "RouteController@updateOrInsert");
    Route::post('routes/update/{route}', "RouteController@update");
    Route::post('routes/destroy', "RouteController@destroy");
    Route::post('routes/status', "RouteController@status");
    Route::post('routes/search', "RouteController@search");
    Route::post('routes/show/business/{route}', "RouteController@show_business");
    //</editor-fold>
    //<editor-fold desc="品名">
    Route::post('products/list', "ProductController@index");
    Route::post('products/store', "ProductController@store");
    Route::post('products/update/{product}', "ProductController@update");
    Route::post('products/update/business/{product}', "ProductController@update_business");
    Route::post('products/destroy', "ProductController@destroy");
    Route::post('products/status', "ProductController@status");
    Route::post('products/show/business/{product}', "ProductController@show_business");
    //</editor-fold>

    //<editor-fold desc="船名">
    Route::post('customerSupplierShipData/list', "CustomerSupplierShipDataController@index");
    Route::post('customerSupplierShipData/update', "CustomerSupplierShipDataController@update");
    Route::post('customerSupplierShipData/update/business/{customerSupplierShipData}', "CustomerSupplierShipDataController@update_business");
    Route::post('customerSupplierShipData/destroy', "CustomerSupplierShipDataController@destroy");
    Route::post('customerSupplierShipData/destroy/business', "CustomerSupplierShipDataController@destroy_business");
    Route::post('customerSupplierShipData/status', "CustomerSupplierShipDataController@status");
    Route::post('customerSupplierShipData/show/business/{customerSupplierShipData}', "CustomerSupplierShipDataController@show_business");
    Route::post('customerSupplierShipData/list/result', "CustomerSupplierShipDataController@list_result_customer_supplier");
    //</editor-fold>

    //<editor-fold desc="客户供应商管理">
    Route::post('customerSuppliers/list', "CustomerSupplierController@index");
    Route::post('customerSuppliers/update', "CustomerSupplierController@update");
    Route::post('customerSuppliers/update/business/{customerSupplier}', "CustomerSupplierController@update_business");
    Route::post('customerSuppliers/show/{customerSupplier}', "CustomerSupplierController@show");
    Route::post('customerSuppliers/show/business/{customerSupplier}', "CustomerSupplierController@show_business");
    Route::post('customerSuppliers/destroy', "CustomerSupplierController@destroy");
    Route::post('customerSuppliers/role', 'CustomerSupplierController@logisticsRole');
    Route::post('customerSuppliers/lock', 'CustomerSupplierController@lock');
    //</editor-fold>

    Route::get('businesses/list/result', "BusinessController@listResult");
    Route::post('businesses/list', "BusinessController@list");

    //<editor-fold desc="合同审批管理">
    Route::group(['middleware' => ['ContractRoleCheck']], function () {
        Route::post("contracts/list", 'ContractController@index');
        Route::post("contracts/show/{contract}", 'ContractController@show');
        Route::post("contracts/update/process0", 'ContractController@update_process0');
        Route::post("contracts/update/process1", 'ContractController@update_process1');
        Route::post("contracts/submit", 'ContractController@submit');
        Route::post("contracts/destroy", 'ContractController@destroy');
        Route::get("contracts/review/list/{contract}", 'ContractController@review_list');
        Route::get("contracts/condition/search", 'ContractController@search');
    });

    //</editor-fold>

    //<editor-fold desc="国家、城市">
    Route::post("cities/list/result", "CityController@show");
    Route::post("cities/update", "CityController@update");
    //</editor-fold>


//    Route::post('contract',);
    //出送还箱点
    Route::post("containerAddresses/list", 'ContainerAddressController@index');
    Route::post("containerAddresses/businessshow/{containerid}", 'ContainerAddressController@businessShow');
    Route::post("containerAddresses/listadd", 'ContainerAddressController@listAdd');
    Route::post("containerAddresses/listupdate", 'ContainerAddressController@listUpdate');
    Route::post("containerAddresses/detailsupdate/{addressid}", 'ContainerAddressController@detailsUpdate');
    Route::post("containerAddresses/destroy", 'ContainerAddressController@destroy');
    Route::post("containerAddresses/statusupdate", 'ContainerAddressController@statusUpdate');
    //箱型对应关系
    Route::post("containerTypes", 'ContainerTypeController@index');
    Route::post("containerTypes/store", 'ContainerTypeController@store');
    Route::post("containerTypes/update", 'ContainerTypeController@update');
    Route::post("containerTypes/destroy", 'ContainerTypeController@destroy');
    //开票类型
    Route::post("InvoiceType/list", 'InvoiceTypeController@index');
    Route::post("InvoiceType/invoiceadd", 'InvoiceTypeController@invoiceAdd');
    Route::post("InvoiceType/statusupdate", 'InvoiceTypeController@statusUpdate');
    Route::post("InvoiceType/invoiceupdate", 'InvoiceTypeController@invoiceUpdate');
    Route::post("InvoiceType/destroy", 'InvoiceTypeController@destroy');
    //业务字段维护
    Route::post("PriceMaintain/list", 'PriceMaintainController@index');
    Route::post("PriceMaintain/priceshow/{priceid}", 'PriceMaintainController@priceShow');
    Route::post("PriceMaintain/detailsupdate/{priceid}", 'PriceMaintainController@detailsUpdate');
    Route::post("PriceMaintain/statusUpdate", 'PriceMaintainController@statusUpdate');
    //费用科目维护
    Route::post("ChargeItem/list", 'ChargeItemController@index');
    Route::post("ChargeItem/chargeitemadd", 'ChargeItemController@chargeItemAdd');
    Route::post("ChargeItem/chargeitemupdate/{id}", 'ChargeItemController@chargeItemUpdate');
    Route::post("ChargeItem/statusupdate", 'ChargeItemController@statusUpdate');
    Route::post("ChargeItem/destroy", 'ChargeItemController@destroy');
    Route::post("ChargeItem/chargeitemshow/{id}", 'ChargeItemController@chargeItemShow');
    Route::post("ChargeItem/detailupdate/{id}", 'ChargeItemController@detailUpdate');
    //价格协议
    Route::post("ChargeRules/list", 'ChargeRuleController@index');
    Route::post("ChargeRules/statusupdate", 'ChargeRuleController@statusUpdate');
    Route::post("ChargeRules/statusverify", 'ChargeRuleController@statusVerify');
    Route::post("ChargeRules/destroy", 'ChargeRuleController@destroy');
    Route::post("ChargeRules/chargeruledatabase", 'ChargeRuleController@chargeRuleDataBase');
    Route::post("ChargeRules/chargeupdate", 'ChargeRuleController@chargeUpdate');
    Route::post("ChargeRules/chargeruledetails/{id}", 'ChargeRuleController@chargeRuleDetails');
    Route::post("ChargeRules/chargeruleexpression/{id}", 'ChargeRuleController@chargeRuleExpression');
    Route::post("ChargeRules/chargeRuleDataUpdate/{id}", 'ChargeRuleController@chargeRuleDataUpdate');
    Route::post("ChargeRules/chargeruledatadelete", 'ChargeRuleController@chargeRuleDataDelete');
    Route::post("ChargeRules/chargerulesexpressionlist/{id}", 'ChargeRuleController@chargeRulesExpressionList');
    Route::post("ChargeRules/chargerulesexpressionupdate/{id}", 'ChargeRuleController@chargeRulesExpressionUpdate');
    Route::post("ChargeRules/chargerulesexpressionbase", 'ChargeRuleController@chargeRulesExpressionBase');
});
Route::post('v1/logout/{user}', 'ApiTokenController@logout');
Route::post('v1/register', 'ApiTokenController@store');
Route::post('v1/login', 'ApiTokenController@login');
Route::post('v1/logout', 'ApiTokenController@logout')->middleware('auth:api');
//Route::get("v1/test1/{id}", "TestsController@show");//->middleware('auth:api');

//Route::resource('photos','PhotoController')->parameters([
//   'photos'=>'admin_user',
//]);SwaggervelServiceProvider
//Route::post("v1/test","Auth\RegisterController@test");
//Route::get("v1/showAge/{id}","Auth\RegisterController@showAge");
//Route::get("v1/benjamin","benjaminController@age");
//Route::prefix("v1")->get("xxx",'PortController@index');

//Route::get('users/{id}', 'CompanyOrganizeController@show');
//Route::get('userss/{xx}', function (App\CompanyOrganize $ad) {
//    die($ad->name);
//});


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
//namespace声明路由组的命名空间，因为上面设置了"prefix"=>"api",所以以下路由都要加一个api前缀，比如请求/api/users_list才能访问到用户列表接口
        $api->group([], function ($api) {
            #管理员可用接口
            #用户列表api
//            $api->get('/users_list','AdminApiController@usersList');
            #添加用户api
//            $api->post('/add_user','AdminApiController@addUser');
            #编辑用户api
//            $api->post('/edit_user','AdminApiController@editUser');
            #删除用户api
//            $api->post('/del_user','AdminApiController@delUser');
            #上传头像api
//            $api->post('/upload_avatar','UserApiController@uploadAvatar');
            $api->get("test/{ClearCompany}", "ClearCompanyController@show");

        });

    });
});
