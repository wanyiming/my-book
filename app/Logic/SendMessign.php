<?php
/**
 * 发送短信的logic层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/4
 * Time: 17:54
 */

namespace App\Logic;

use App\Models\DxCaptcha;
use App\Libraries\Sms\Emay\Client;
use Exception;
use Throwable;
use Log;

class SendMessignLogic
{
    private static $configArray = array();
    private static $client      = NULL;
    private static $isInit      = false;

    public static function getClient()
    {
        if (is_null(self::$client)) {
            self::$configArray = config('sendmobile.SEND_MSG_PAREN');

            self::$client = new Client([
                'serialNo'   => self::$configArray['SERIES'],
                'key'        => self::$configArray['SESSION_KEY'],
                'serialPass' => self::$configArray['PWD'],
                'url'        => self::$configArray['URL'],
                'timeout'    => 10,//超时10秒
                'ns'         => 'http://sdkhttp.eucp.b2m.cn/'
            ]);
        }

        return self::$client;
    }

    /**
     * 手机号
     * @param string $mobile
     * @param int $type 验证码类型改 1:注册；2：动态码登录；3：找回；4：修改密码（预注册用户修改密码时使用）；5：认证；6：普通验证码；7：到期提醒（20161012改）;8：绑定银行卡
     * @param string $content 发送内容；
     * @return bool
     * 默认发送短信的类型 1： 注册时发送验证码；  2： 找回密码； 3： 发送普通验证码； 4：认证手机号； 5：验证手机号； 6：到期提醒
     */
    public static function sendMessign($mobile = '', $type = 1, $content = '')
    {
        try {
            if (!$mobile) {
                return false;
            }

            $client = self::getClient();
            if (!self::$isInit) {
                $statusCode = $client->registerEx();
                if (!(is_numeric($statusCode) && empty($statusCode))) {
                    return false;
                }
                self::$isInit = true;
            }

            $code = random_char();
            if ($content) {
                $content = '【问问我】' . str_replace('{{code}}', $code, $content);
            } else {
                $content = '【问问我】您的验证码是：' . $code . '。请不要把验证码泄露给其他人。';
            }

            $status = $client->setParameter('mobiles', (array)$mobile)
                ->setParameter('smsContent', $content)
                ->sendSms();

            if ($status == 0 && $status != null) {
                Log::info('接收人ip:' . get_client_ip() . ';接收电话:' . $mobile . ';接收内容:' . $content);
                /**
                 * 到时候根据情况来定做下面的信息；
                 * 1：存入数据库
                 * 2：添加日志文件
                 */
                $add_data['create_time'] = time();
                $add_data['code'] = $code;
                $add_data['typeid'] = $type;
                $add_data['mobile'] = $mobile;
                $add_data['ip'] = get_client_ip();
                DxCaptcha::create($add_data);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }
        return false;
    }
}