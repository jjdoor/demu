<?php

namespace App\Http\Controllers;

use App\Service\AliyuncsOssService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group 公用方法100
 */
class CommonController extends Controller
{
    /**
     * 文件上传10001
     *
     * @bodyParam attachment string required 上传的文件，只可上传pdf、doc、docx格式
     * @response {
     *    "original_name":"原文件名",
     *    "name":"重新命名"
     *    "size":3390890,
     *    "ext":"pdf"
     * }
     * @param Request $request
     * @return array|null
     * @throws \OSS\Core\OssException
     */
    public function upload(Request $request)
    {
        return AliyuncsOssService::uploadFile($request, null, null, false);
    }

    /**
     * 文件预览10002
     *
     * @jsonParam name string required 文件名 Example:2019-08-23-10-13-36-1392.pdf
     * @response {
     * }
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     * @throws \OSS\Core\OssException
     */
    function preview(Request $request)
    {
        $messages = [];
        $rules = [
            "name" => [
                "required",
                Rule::exists("attachments", "name")
            ]
        ];
        $this->validate($request, $rules, $messages);

        AliyuncsOssService::preview($request->input('name'));
    }
}
