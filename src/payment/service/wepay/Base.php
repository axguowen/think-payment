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

namespace think\payment\service\wepay;

use think\payment\Service;
use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;

/**
 * 微信支付服务基础类
 */
abstract class Base extends Service
{
    /**
     * 动态调用
     * @param string $method
     * @param array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->handler->getChainable()->$method(...$parameters);
    }
}
