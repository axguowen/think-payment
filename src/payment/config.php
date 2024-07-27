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

return [
    // 默认平台
    'default' => 'alipay',
    // 平台配置
    'platforms' => [
        // 支付宝支付平台
        'alipay' => [
            // 平台驱动
            'type' => 'Alipay',
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
        ],
        // 微信支付平台
        'wepay' => [
            // 平台驱动
            'type' => 'Wepay',
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
        ],
    ],
];
