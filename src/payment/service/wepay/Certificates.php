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
use WeChatPay\Crypto\AesGcm;

/**
 * 微信支付证书服务
 */
class Certificates extends Base
{
    /**
     * 下载证书
     * @access public
     * @return array
     */
    public function download()
    {
        try{
            // 获取下载证书响应
            $response = $this->handler
                            ->getChainable()
                            ->chain('v3/certificates')
                            ->get();
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
        
        // 如果证书数据为空
        if(!isset($bodyData['data']) || empty($bodyData['data'])){
            // 返回
            return [null, new \Exception('未获取到有效证书')];
        }
        // 当前请求时间
        $requestTime = time();
        // 获取加密的证书数据列表
        $encryptedCerts = $bodyData['data'];
        // 当前已选择的证书数据
        $encryptedCertSelected = null;
        // 遍历证书数据
        foreach ($encryptedCerts as $encryptedCert) {
            // 获取当前证书的启用时间
            $effectiveTime = strtotime($encryptedCert['effective_time']);
            // 还未到启用时间
            if ($effectiveTime > $requestTime) {
                // 继续下一个证书
                continue;
            }
            // 获取当前证书的到期时间
            $expireTime = strtotime($encryptedCert['expire_time']);
            // 已经到期
            if ($expireTime < $requestTime) {
                // 继续下一个证书
                continue;
            }
            // 已选择的证书为空或者到期时间比当前证书早
            if (is_null($encryptedCertSelected) || $encryptedCertSelected['expire_time'] < $expireTime) {
                // 更新选中的证书为当前证书
                $encryptedCertSelected = [
                    'serial_no' => $encryptedCert['serial_no'],
                    'effective_time' => $effectiveTime,
                    'expire_time' => $expireTime,
                    'encrypt_certificate' => $encryptedCert['encrypt_certificate'],
                ];
            }
        }

        // 获取证书加密数据
        $encryptCertificate = $encryptedCertSelected['encrypt_certificate'];
        // 加密后的证书内容
        $ciphertext = $encryptCertificate['ciphertext'];
        // APIv3密钥
        $apiv3Key = $this->handler->getConfig('apiv3_key');
        // 随机串
        $nonce = $encryptCertificate['nonce'];
        // 加密证书的附加数据
        $associatedData = $encryptCertificate['associated_data'];
        // 解密
        $certContent = AesGcm::decrypt($ciphertext, $apiv3Key, $nonce, $associatedData);
        // 更新配置
        $this->handler->setConfig([
            'wepay_cert' => $certContent,
            'wepay_cert_serial' => $encryptedCertSelected['serial_no'],
        ]);
        // 返回
        return [[
            'cert_serial' => $encryptedCertSelected['serial_no'],
            'cert_content' => $certContent,
            'effective_time' => $encryptedCertSelected['effective_time'],
            'expire_time' => $encryptedCertSelected['expire_time'],
        ], null];
    }
}
