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

namespace think\payment\service\alipay;

use think\payment\Service;

/**
 * 支付宝服务基础类
 */
abstract class Base extends Service
{
    /**
     * 当前客户端
     * @var mixed
     */
	protected $client;

    /**
     * 动态调用
     * @param string $method
     * @param array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->client->$method(...$parameters);
    }
}
