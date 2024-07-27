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

use Alipay\EasySDK\Payment\Wap\Client;

/**
 * 支付宝手机网站支付服务
 */
class Wap extends Base
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
}
