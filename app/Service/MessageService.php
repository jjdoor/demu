<?php
/**
 * Author: jkwu
 * Date: 2019/8/23
 * Time: 9:27
 * Description:sms
 */
namespace App\Service;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
class MessageService
{

    function __construct()
    {
        $this->accessKeyId = env('ALIYUN_SMS_ACCESSKEYID');
        $this->accessKeySecret = env('ALIYUN_SMS_ACCESSKEYSECRET');
        $this->signName = env('ALIYUN_SMS_SIGNNAME');
    }

    /**
     * @param $phone   手机号
     * @param $sms_code   短信模板Code
     * @param $sms_data = array('key1'=>'value1','key2'=>'value2', …… )
     * @return json
     */

    public  function sendSms($phone,$sms_code,$sms_data){

        //初始化配置
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $data=AlibabaCloud::rpc()
                ->product('Dysmsapi')
                 ->scheme('https')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $phone,
                        'TemplateCode' => $sms_code,
                        'SignName' => $this->signName,
                        'TemplateParam' => json_encode($sms_data),
                    ],
                ])
                ->request();
            return $data;
        } catch (ClientException $e) {
            Log::error($e->getErrorMessage() . PHP_EOL);
            return false;
        } catch (ServerException $e) {
            Log::error($e->getErrorMessage() . PHP_EOL);
            return false;
        }
    }

   
}