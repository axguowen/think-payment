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

use Alipay\EasySDK\Payment\FaceToFace\Client;

/**
 * 支付宝当面付服务
 */
class FaceToFace extends Base
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
     * 创建预支付二维码
     * @access public
     * @param string $subject
     * @param string $outTradeNo
     * @param string $totalAmount
     * @return array
     */
    public function preCreate($subject, $outTradeNo, $totalAmount)
    {
        // 获取预支付二维码创建响应
        $response = $this->client->preCreate($subject, $outTradeNo, $totalAmount);
        // 如果请求失败
        if ($response->code != '10000'){
            return [null, new \Exception($response->msg, $response->code)];
        }

        return [[
            'out_trade_no' => $response->outTradeNo,
            'qr_code' => $response->qrCode,
        ], null];
    }
}
