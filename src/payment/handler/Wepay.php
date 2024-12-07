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
use WeChatPay\Builder;
use WeChatPay\BuilderChainable;
use WeChatPay\Crypto\Rsa;

/**
 * 微信支付平台驱动
 */
class Wepay extends Base
{
    /**
     * 微信支付SDK实例
     * @var BuilderChainable
     */
    protected $chainable;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 商户号, 服务商模式下为服务商商户号, 可在 账户中心->商户信息 查看
        'mch_id' => '',
        // 公众号ID
        'mp_appid' => '',
        // APIv3密钥(32字节, 形如md5值), 可在 账户中心->API安全 中设置
        'apiv3_key' => '',
        // 商户私钥 字符串或路径, 即 API证书 PRIVATE KEY, 可在 账户中心->API安全->申请API证书 里获得
        // 文件名形如: file:///www/your/dir/apiclient_key.pem
        'apiclient_key' => '',
        // 商户公钥证书字符串或路径, 即 API证书 CERTIFICATE, 可在 账户中心->API安全->申请API证书 里获得
        // 文件名形如: file:///www/your/dir/apiclient_cert.pem
        'apiclient_cert' => '',
        // 商户API证书的序列号, 如: 3775B6A45ACD****
        'apiclient_cert_serial' => '',
        // 微信支付平台证书
        'wepay_cert' => '',
        // 微信支付平台证书序列号
        'wepay_cert_serial' => '',
        // 微信回调url
        'notify_url' => '',
        // 接入点
        'base_uri' => 'https://api.mch.weixin.qq.com',
        // 选填-是否沙箱模式
        'is_sandbox' => false,
    ];

    /**
     * 服务的命名空间
     * @var string
     */
    protected $serviceNamespace = '\\think\\payment\\service\\wepay\\';

    /**
     * 初始化
     * @access protected
     * @return void
     */
    protected function init()
    {
        // 读取私钥证书
        $privateKey = Rsa::from($this->options['apiclient_key'], Rsa::KEY_TYPE_PRIVATE);
        // 微信支付平台证书
        $certs = ['any' => null];
        // 如果指定了微信支付平台证书
        if(!empty($this->options['wepay_cert']) && !empty($this->options['wepay_cert_serial'])) {
            $certs = [
                $this->options['wepay_cert_serial'] => Rsa::from($this->options['wepay_cert'], Rsa::KEY_TYPE_PUBLIC),
            ];
        }
        // 实例化客户端
        $this->chainable = Builder::factory([
            'mchid' => $this->options['mch_id'],
            'serial' => $this->options['apiclient_cert_serial'],
            'privateKey' => $privateKey,
            'certs' => $certs,
            'base_uri' => $this->options['base_uri'],
        ]);
    }

    /**
     * 获取支付宝SDK实例
     * @access public
     * @return BuilderChainable
     */
    public function getChainable()
    {
        return $this->chainable;
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