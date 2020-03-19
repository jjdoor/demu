<?php
/**
 * Author: jkwu
 * Date: 2019/8/23
 * Time: 14:36
 * Description:
 */
use App\Service\MessageService;//阿里云短信

 /**
  *@name 阿里云短信发送 
 * @param $phone   手机号 多个号码以英文逗号隔开
 * @param $sms_code   短信模板Code
 * @param $sms_data = array('key1'=>'value1','key2'=>'value2', …… )
 * @return json
 */
function aLiYunSendSms($phone,$sms_code,$sms_data=array())
{ 
	 if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){

	 	return false;
	 }
	  if($sms_code==""){

	 	return false;
	 }

	 $MessageService = new MessageService();

	 $data=$MessageService->sendSms($phone,$sms_code,$sms_data);
	 return json_decode($data,TRUE);
}

