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

/**
 * 微信支付平台驱动
 */
class Wepay extends Base
{
	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // 必填-商户号，服务商模式下为服务商商户号
        // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
        'mch_id' => '',
        // 必填-v3 商户秘钥
        // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
        'mch_secret_key' => '',
        // 必填-商户私钥 字符串或路径
        // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
        // 文件名形如：apiclient_key.pem
        'mch_secret_cert' => '',
        // 必填-商户公钥证书路径或路径
        // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
        // 文件名形如：apiclient_cert.pem
        'mch_public_cert' => '',
        // 选填-微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
        'wechat_public_cert' => [],
        // 必填-微信回调url
        // 不能有参数，如?号，空格等，否则会无法正确回调
        'notify_url' => '',
        // 选填-是否沙箱模式
        'is_sandbox' => false,
    ];

    /**
     * 服务的命名空间
     * @var string
     */
    protected $serviceNamespace = '\\think\\payment\\service\\wepay\\';
}