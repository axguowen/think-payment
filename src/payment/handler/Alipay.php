<?php
// +----------------------------------------------------------------------
// | ThinkPHP Payment [Simple Payment Extension For ThinkPHP]
// +----------------------------------------------------------------------
// | ThinkPHP 支付扩展
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace think\payment\handler;

use think\Response;
use think\payment\utils\AntCertificationUtil;
use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\EasySDKKernel;

/**
 * 支付宝支付平台驱动
 */
class Alipay extends Base
{
    /**
     * 支付宝SDK实例
     * @var EasySDKKernel
     */
    protected $kernel;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 必填-支付宝分配的 app_id
        'app_id' => '',
        // 必填-应用私钥字符串
        // 在 https://open.alipay.com/develop/manage 《应用详情->开发设置->接口加签方式》中设置
        'app_private_key' => '',
        // 证书模式
        // 必填-应用公钥证书字符串或路径
        'app_public_cert' => '',
        // 必填-支付宝公钥证书字符串或路径
        'alipay_public_cert' => '',
        // 必填-支付宝根证书字符串或路径
        'alipay_root_cert' => '',
        // 注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        // 支付宝公钥
        'alipay_public_key' => '',
        // 跳转地址
        'return_url' => '',
        // 回调地址
        'notify_url' => '',
        // 选填-第三方应用授权token
        'app_auth_token' => '',
        // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
        'service_provider_id' => '',
        // 选填-是否沙箱模式
        'is_sandbox' => false,
    ];

    /**
     * 服务的命名空间
     * @var string
     */
    protected $serviceNamespace = '\\think\\payment\\service\\alipay\\';

	/**
     * 初始化
     * @access protected
     * @return void
     */
    protected function init()
    {
        $this->kernel = null;
    }

    /**
     * 获取支付宝SDK实例
     * @access public
     * @return EasySDKKernel
     */
    public function getKernel()
    {
        // 如果不存在
        if(is_null($this->kernel)){
            // 实例化配置对象
            $config = new Config();
            // 协议
            $config->protocol = 'https';
            // 网关主机
            $config->gatewayHost = !$this->options['is_sandbox'] ? 'openapi.alipay.com' : 'openapi-sandbox.dl.alipaydev.com';
            // 加签类型
            $config->signType = 'RSA2';
            // 应用ID
            $config->appId = $this->options['app_id'];
            // 应用私钥
            $config->merchantPrivateKey = $this->options['app_private_key'];
            // 支付宝证书公钥
            $config->alipayPublicKey = $this->options['alipay_public_key'];
            // 证书模式
            if(!empty($this->options['app_public_cert'])){
                $config->alipayPublicKey = AntCertificationUtil::getPublicKey($this->options['alipay_public_cert']);
                $config->alipayRootCertSN = AntCertificationUtil::getRootCertSN($this->options['alipay_root_cert']);
                $config->merchantCertSN = AntCertificationUtil::getCertSN($this->options['app_public_cert']);
            }
            //可设置异步通知接收服务地址
            $config->notifyUrl = $this->options['notify_url'];
            // 实例化SDK
            $this->kernel = new EasySDKKernel($config);
        }
        // 返回
        return $this->kernel;
    }

    /**
     * 返回成功信息
     * @access public
     * @return Response
     */
    public function success()
    {
        return Response::create('success')->contentType('text/plain');
    }
}