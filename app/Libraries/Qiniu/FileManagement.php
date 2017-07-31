<?php
namespace App\Libraries\Qiniu;

use Illuminate\Http\Request;
use Log;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * 七牛上传类
 * Created by PhpStorm.
 * Class:
 * User: wym
 * Date: 2017/2/15
 * Time: 10:37
 */
class FileManagement
{
    const DOMIN_CALLBACK = '';
    const DOMAIN = 'http://otqjojrc1.bkt.clouddn.com/'; //文件的默认域名

    public static $configArr = [
        'access_key' => null, //秘钥
        'secret_key' => null, //秘钥
        'bucket' => null, //空间名
        'key' => null, //得到uploadToken的值 参数可以不用初始化
        'expires' => 3306, //请求时间
        'policy' => null/*array(
            'callbackUrl' => self::DOMIN_CALLBACK.'/callback.',
            'callbackBody' =>'{"fname":"$(fname)", "fkey":"$(key)", "desc":"$(x:desc)", "uid":"645", "filename":"$(fname)", "filesize":"$(fsize)"}'
        ),*/
    ];

    public function __construct($bucketName = null)
    {
        self::$configArr['access_key'] = config('qiniu.access_key');
        self::$configArr['secret_key'] = config('qiniu.secret_key');
        self::$configArr['bucket']     = $bucketName ?? config('qiniu.bucket');
    }


    public function __set($name, $value)
    {
        self::$configArr[$name] = $value;
        // TODO: Implement __set() method.
    }

    protected function getAuthObject () {
        return (new Auth(self::$configArr['access_key'], self::$configArr['secret_key']));
    }


    public function getUploadToken()
    {
        $uploadToken =  self::getAuthObject()->uploadToken(self::$configArr['bucket'], self::$configArr['key'], self::$configArr['expires'], self::$configArr['policy']);
        if (empty($uploadToken)) {
            throw new \Exception('Failed to get oploadToken');
        }
        return $uploadToken;
    }


    public function fileUpload($filePath = '')
    {
        if (empty($filePath)) {
            return false;
        }
        //TODO 先检查文件是否能正常上传，不能就直接返回提示
        list ($ret, $err) = (new UploadManager())->putFile($this->getUploadToken(), self::$configArr['key'],$filePath);
        if ($err !== null) {
            // 如果上传出错
            throw new \Exception($err);
        }
        return $ret;
    }

    // 文件上传（正在使用）
    public function testFileUpload($filename,$filePath)
    {
        //TODO 先检查文件是否能正常上传，不能就直接返回提示
        list ($ret, $err) = (new UploadManager())->putFile($this->getUploadToken(), $filename,$filePath);
        if ($err !== null) {
            throw new \Exception($err);
        }
        return $ret;
    }

    // 文件下载（正在使用）
    public function testFileDownload($file_key,$file_name)
    {

        // 构建鉴权对象
        $auth = self::getAuthObject();
        //baseUrl构造成私有空间的域名/key的形式
        $domainUrl = env('RESOURCE_DOMAIN');
        $downloadUrl = $domainUrl.$file_key.'?attname='.urlencode($file_name);
        $authUrl = $auth->privateDownloadUrl($downloadUrl);
        return $authUrl;

    }

    public function fileDownlode($fileKey = '')
    {
        //TODO 组装图片地址
        return $fileKey ? self::getAuthObject()->privateDownloadUrl(self::DOMAIN  . $fileKey) : '';
    }

    public function fileCallback()
    {
        $callbackBody = file_get_contents('php://input');
        $contentType = 'application/x-www-form-urlencoded';
        //TODO 回调的签名信息，可以验证该回调是否来自七牛
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $isQiniuCallback = (new Auth(self::$configArr['access_key'], self::$configArr['secret_key']))->verifyCallback($contentType, $authorization, self::$configArr['policy']['callbackUrl'], $callbackBody);
        if ($isQiniuCallback) {
            $resp = array('ret' => 'success');
        } else {
            $resp = array('ret' => 'failed');
        }
        echo json_encode($resp);
    }


    public function fileDelete($fileKey = '')
    {
        if (empty($fileKey)) {
            return false;
        }
        try {
            $err  = (new BucketManager(self::getAuthObject()))->delete(self::$configArr['bucket'], $fileKey);
            if ($err !== null) {
                throw new \Exception('文件删除失败:'.$fileKey);
            } else {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        return false;
    }


    public function fileLists()
    {

    }


    public function fileRemove()
    {

    }

}
