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

use GuzzleHttp\Exception\RequestException;

/**
 * 微信支付服务
 */
class Transactions extends Base
{
    /**
     * Native下单
     * @access public
     * @param string $subject
     * @param string $outTradeNo
     * @param string $totalAmount
     * @return array
     */
    public function native($subject, $outTradeNo, $totalAmount)
    {
        try {
            // 获取预支付二维码创建响应
            $response = $this->handler->getChainable()
            ->chain('v3/pay/transactions/native')
            ->post(['json' => [
                'mchid'        => $this->handler->getConfig('mch_id'),
                'out_trade_no' => $outTradeNo,
                'appid'        => $this->handler->getConfig('mp_appid'),
                'description'  => $subject,
                'notify_url'   => $this->handler->getConfig('notify_url'),
                'amount'       => [
                    'total'    => $totalAmount,
                    'currency' => 'CNY',
                ],
            ]]);
        } catch(\Exception $e){
            // 如果不是请求异常或者没有响应，则抛出异常
            if (!($e instanceof RequestException) || !$e->hasResponse()) {
                // 返回
                return [null, $e];
            }
            // 获取响应
            $response = $e->getResponse();
        }
        // 获取响应数据
        $bodyData = json_decode((string) $response->getBody(), true);
        // 解析失败
        if(!is_array($bodyData)){
            // 返回
            return [null, new \Exception('响应数据解析失败', $response->getStatusCode())];
        }
        // 如果响应状态码不是200
        if ($response->getStatusCode() != 200){
            // 错误信息
            $message = isset($bodyData['code']) ? $bodyData['code'] : '请求接口失败';
            // 如果存在错误信息
            if(isset($bodyData['message'])){
                $message = $bodyData['message'];
            }
            // 返回
            return [null, new \Exception($message, $response->getStatusCode())];
        }
        // 返回
        return [[
            'out_trade_no' => $outTradeNo,
            'qr_code' => $bodyData['code_url'],
        ], null];
    }
}
