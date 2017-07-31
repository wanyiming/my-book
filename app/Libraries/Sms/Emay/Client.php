<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 8:51
 * Author: dch
 */
namespace App\Libraries\Sms\Emay;

use SoapClient;
use SoapParam;
use LogicException;
use Symfony\Component\HttpFoundation\ParameterBag;

class Client
{
    protected $client;

    public function __construct(array $parameters = [])
    {
        $this->client = new SoapClient(null, [
                'location'           => "http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService",
                'uri'                => "http://sdkhttp.eucp.b2m.cn/",
                'style'              => SOAP_RPC,//SOAP_RPC,SOAP_DOCUMENT
                'use'                => SOAP_ENCODED,//SOAP_ENCODED,SOAP_LITERAL
                'soap_version'       => SOAP_1_1,
                'encoding'           => 'UTF-8',
                'connection_timeout' => 10,//连接超时10秒
                'cache_wsdl'         => WSDL_CACHE_NONE
            ]
        );
        $this->parameters = new ParameterBag();
        $parameters = array_merge($this->getDefaultParameters(), $parameters);

        $this->setParameters($parameters);
    }

    /**
     * 设置单个参数
     *
     * @param $key
     * @param $value
     * @return $this
     * @author dch
     */
    public function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * 批量设置参数
     *
     * @param array $parameters
     * @return $this
     * @author dch
     */
    public function setParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * 注册登记
     *
     * @return bool|mixed
     * @author dch
     */
    public function registerEx()
    {
        $paramMap = [
            'arg0' => 'serialNo',
            'arg1' => 'key',
            'arg2' => 'serialPass',
        ];

        $sendParams = $this->assembleParams($paramMap);

        return $this->call('registEx', $sendParams);
    }

    /**
     * 短信发送
     *
     * @return bool|mixed  成功返回 true
     * @author dch
     */
    public function sendSms()
    {
        $paramMap = [
            'arg0' => 'serialNo',
            'arg1' => 'key',
            'arg2' => 'sendTime',
            'arg3' => 'mobiles',
            'arg4' => 'smsContent',
            'arg5' => 'addSerial',
            'arg6' => 'srcCharset',
            'arg7' => 'smsPriority',
            'arg8' => 'smsId',
        ];

        $sendParams = $this->assembleParams($paramMap);

        return $this->call('sendSMS', $sendParams);
    }

    public function getBalance()
    {
        $paramMap = [
            'arg0' => 'serialNo',
            'arg1' => 'key',
        ];
        $sendParams = $this->assembleParams($paramMap);
        return $this->call("getBalance",$sendParams);
    }

    /**
     * 调用远程函数
     *
     * 成功返回true or 0
     *
     * @param string $functionName
     * @param array $sendParams
     * @return bool|mixed
     * @author dch
     */
    public function call($functionName, array $sendParams)
    {
        return $this->client->__call($functionName, $sendParams);
    }

    /**
     * 组装参数
     *
     * @param array $paramMap
     * @return array
     * @author dch
     */
    protected function assembleParams(array $paramMap)
    {
        $sendParams = [];
        foreach ($paramMap as $argKey => $paramKey) {
            $value = $this->parameters->get($paramKey);
            if (is_null($value)) {
                throw new LogicException(sprintf('参数必须:%s=%s', $paramKey, $value));
            }

            if ('mobiles' == $paramKey) {
                foreach ((array)$this->parameters->get($paramKey) as $mobileNumber) {
                    $sendParams[] = new SoapParam($mobileNumber, $argKey);
                }
            } else {
                $sendParams[] = new SoapParam($this->parameters->get($paramKey), $argKey);
            }
        }

        return $sendParams;
    }

    /**
     * 设置商家序列号
     *
     * @param $serialNo
     * @return $this
     * @author dch
     */
    public function setSerialNo($serialNo)
    {
        $this->setParameter('serialNo', $serialNo);

        return $this;
    }

    /**
     * 设置自定义key值
     *
     * @param $key
     * @return $this
     * @author dch
     */
    public function setKey($key)
    {
        $this->setParameter('key', $key);

        return $this;
    }

    /**
     * 设置密码字段
     *
     * @param $serialPass
     * @return $this
     * @author dch
     */
    public function setSerialSass($serialPass)
    {
        $this->setParameter('serialPass', $serialPass);

        return $this;
    }

    /**
     * 默认参数
     *
     * @return array
     * @author dch
     */
    public function getDefaultParameters()
    {
        return [
            'sendTime'    => '',//空代表即时发送
            'addSerial'   => '',//扩展号码,默认空
            'srcCharset'  => 'UTF-8',//编码
            'smsPriority' => 5,//短信优先级,1-5级
            'smsId'       => 8888,//信息序列ID,唯一的正整数
        ];
    }

}