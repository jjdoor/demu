<?php
/**
 * Created by PhpStorm.
 * User: guojianhang
 * Date: 2018/2/7
 * Time: 20:41
 */
$registry = [
    [
        'name'=>'mobile',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\Mobile()
    ],
    [
        'name'=>'postcode',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\PostCode()
    ],
    [
        'name'=>'phone',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\Phone()
    ],
    [
        'name'=>'cn',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\Chinese()
    ],
    [
        'name'=>'cn_dash',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\ChineseDash()
    ],
    [
        'name'=>'cn_num',
        'obj'=>new \Waterloocode\LaravelValidationExtend\Rules\ChineseNum()
    ],
];

foreach ($registry as $item)
{
    $rules = $item['obj'];
    \Validator::replacer($item['name'], function ($message, $attribute, $rule, $parameters) use ($rules) {
        return $rules->message();
    });

    \Validator::extend($item['name'],function ($attribute, $value, $parameters, $validator) use ($rules){
        return $rules->passes($attribute, $value);
    });
}

/**
 * 参考https://learnku.com/articles/5100/laravel-validator-uses-notes
 *    https://learnku.com/articles/8038/laravel-validation-extension-package
 */
// 扩展验证方法
Validator::extend('valid_code',function ($attribute, $value, $parameters, $validator){
    return strlen($value) == 5;
},'编号不符合 :valid_year 年的要求');

// extendImplicit与extend的区别: 即使该字段规则中没有required也执行该验证
Validator::extendImplicit('valid_code',function ($attribute, $value, $parameters, $validator){
    return strlen($value) == 5;
},'编号不符合 :valid_year 年的要求');

// 替换该验证规则中的占位符
Validator::replacer('valid_code', function($message, $attribute, $rule, $parameters) {
    return str_replace(':valid_year','2017',$message);
});

\Illuminate\Validation\Validator::extend("mobile", function ($attribute,$value,$parameters,$validator){
    if (strlen($value) !== 11) {
        return false;
    }
    if (!is_numeric($value)) {
        return false;
    }
    // 中国移动
    $cmcc
        = '((13[4-9])|(147)|(15[0-2,7-9])|(17[8])|(18[2-4,7-8]))[0-9]{8}|(170[5])[0-9]{7}';
    //中国联通
    $uniform
        = '((13[0-2])|(145)|(15[5-6])|(17[156])|(18[5,6])|(16[6]))[0-9]{8}|(170[4,7-9])[0-9]{7}';
    //电信
    $telecom
        = '((133)|(149)|(153)|(17[3,7])|(18[0,1,9])|(19[9]))[0-9]{8}|(170[0-2])[0-9]{7}';

    $patterns = '/\A%s\z/D';//限定开头和结尾

    return preg_match(sprintf($patterns, $cmcc), $value)
        || preg_match(sprintf($patterns, $uniform), $value)
        || preg_match(sprintf($patterns, $telecom), $value);
}, '手机号格式不正确');

//$validator = Validator::make($input,[
//    'code'=>'required|valid_code',
//]);
// code.valid_code 若错误返回信息"编号不符合 2017 年的要求"


