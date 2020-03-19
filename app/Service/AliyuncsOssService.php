<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-08-03
 * Time: 16:12
 * 参考文档：https://help.aliyun.com/document_detail/88518.html?spm=a2c4g.11186623.6.1061.40f4a85dSDbwLs
 */

namespace App\Service;

use App\Attachment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use OSS\Core\OssException;
use OSS\OssClient;

class AliyuncsOssService
{
    public $ossClient;
    public $bucket;

    /**
     * AliyuncsOssService constructor.
     * @throws OssException
     */
    function __construct()
    {
        $accessKeyId = Config::get('aliyuncs_oss.accessKeyId');
        $accessKeySecret = Config::get('aliyuncs_oss.accessKeySecret');
        $endpoint = Config::get('aliyuncs_oss.endpoint');
        $this->bucket = Config::get('aliyuncs_oss.bucket');
        $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    }

    /**
     * @param $request
     * @param string|null $model
     * @param int|null $foreign_key
     * @param bool $is_use_original_name
     * @param array $limit
     * @return array|null
     * @throws OssException
     */
    static function uploadFile(
        $request,
        string $model = null,
        int $foreign_key = null,
        bool $is_use_original_name = false,
        array $limit = ['pdf', 'doc', 'docx'])
    {
        /** @var Request $request */
        $fileCharater = $request->file('attachment');
        $ext = $fileCharater->getClientOriginalExtension();
        $size = $fileCharater->getSize();
        $o_name = $fileCharater->getClientOriginalName();
        //获取文件的绝对路径
        $filePath = $fileCharater->getRealPath();
        //定义文件名
        if ($is_use_original_name) {
            $filename = $o_name;
        } else {
            $filename = date('Y-m-d-h-i-s') . '-' . rand(1000, 9999) . '.' . $ext;
        }

        $self = new self();
        try {
            $self->ossClient->uploadFile($self->bucket, $filename, $filePath);

            if ($foreign_key && $model) {
                $attachment = new Attachment();
                $attachment->name = $filename;
                $attachment->size = $size;
                $attachment->ext = $ext;
                $attachment->foreign_key = $foreign_key;
                $attachment->model = $model;
                $attachment->save();
            }
        } catch (OssException $e) {
            return null;
        }

        return ['name' => $filename, 'original_name' => $o_name, 'size' => $size, 'ext' => $ext];
    }

    /**
     * @param $remote_filename
     * @return bool
     */
    static function deleteObject($remote_filename)
    {
        try {
            $self = new self();

            $self->ossClient->deleteObject($self->bucket, $remote_filename);
        } catch (OssException $e) {
            return false;
        }
        return true;
    }

    /**
     * pdf文档预览，doc、docx文档下载
     * @param $filename
     * @throws OssException
     */
    static function preview($filename)
    {
        $ext = strtolower(pathinfo($filename)['extension']);
        if ($ext == "pdf") {
            header('Content-type: application/pdf');
        } elseif ($ext == 'doc' || $ext == 'docx') {
            header('Content-type: application/msword');
        }
        header('filename=' . rand(1000, 9999) . ".pdf");

        $object = self::getObject($filename);

        die($object);
    }

    /**
     * @param $remote_filename 需要下载的文件名
     * @param string $rename_filename 下载到本地重新命名远程文件，为空不重新命名
     * @return bool
     * @throws OssException
     */
    static function getObject($remote_filename, $rename_filename = '')
    {
        $self = new self();
        try {
//            $options = array(
//                OssClient::OSS_FILE_DOWNLOAD => empty($rename_filename) ? $remote_filename : "..\storage\attachment\\" . $rename_filename
//            );
            $content = $self->ossClient->getObject($self->bucket, $remote_filename);//, $options);
        } catch (OssException $e) {
            return false;
        }
        return $content;
    }
}