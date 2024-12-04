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

use WeChatPay\Crypto\Rsa;
use WeChatPay\Crypto\AesGcm;
use WeChatPay\Formatter;

/**
 * 微信支付公共服务
 */
class Common extends Base
{
    /**
     * 获取通知数据
     * @access public
     * @return array
     */
    public function getNotifyData($inBody, $signature, $nonce, $timestamp)
    {
        // 如果时间戳与服务器时间相差较大，则验证失败
        if(300 < abs(Formatter::timestamp() - (int)$timestamp)){
            // 返回
            return [null, new \Exception('异步通知数据时间戳验证失败')];
        }
        // 读取微信支付平台证书
        $wepayCert = Rsa::from($this->handler->getConfig('wepay_cert'), Rsa::KEY_TYPE_PUBLIC);
        // 验签字符串
        $message = Formatter::joinedByLineFeed($timestamp, $nonce, $inBody);
        // 获取验签状态
        $verifiedStatus = Rsa::verify($message, $signature, $wepayCert);
        // 验签失败
        if(!$verifiedStatus){
            // 返回
            return [null, new \Exception('异步通知数据签名验证失败')];
        }
        // APIv3密钥
        $apiv3Key = $this->handler->getConfig('apiv3_key');
        // 转换通知的JSON文本消息为数组
        $inBodyData = json_decode($inBody, true);
        // 失败
        if(!is_array($inBodyData)){
            // 返回
            return [null, new \Exception('异步通知数据解析失败')];
        }
        // 获取加密数据内容
        $ciphertext = $inBodyData['resource']['ciphertext'];
        // 随机串
        $nonce = $inBodyData['resource']['nonce'];
        // 附加数据
        $associatedData = $inBodyData['resource']['associated_data'];
        // 解密数据
        $inBodyDecrypted = AesGcm::decrypt($ciphertext, $apiv3Key, $nonce, $associatedData);
        // 解析JSON数据
        $inBodyDecryptedData = json_decode($inBodyDecrypted, true);
        // 成功
        if(is_array($inBodyDecryptedData)){
            // 返回
            return [$inBodyDecryptedData, null];
        }
        // 返回
        return [null, new \Exception('异步通知数据不合法')];
    }
}
