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

use Alipay\EasySDK\Payment\Common\Client;

/**
 * 支付宝公用支付服务
 */
class Common extends Base
{
	/**
     * 初始化
     * @access protected
     * @return void
     */
    protected function init()
    {
        // 实例化客户端
        $this->client = new Client($this->handler->getKernel());
    }

    /**
     * 获取异步通知数据
     * @access public
     * @param string[] $parameters
     * @return array
     */
    public function getNotifyData(array $parameters)
    {
        // 异步通知数据验证成功
        if ($this->client->verifyNotify($parameters)) {
            return [$parameters, null];
        }
        // 异步通知数据验证失败
        return [null, new \Exception('异步通知数据验证失败')];
    }
}
