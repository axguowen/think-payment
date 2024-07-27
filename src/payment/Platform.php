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

namespace think\payment;

use think\facade\App;
use think\helper\Str;
use think\payment\contract\HandlerInterface;

/**
 * 支付平台类
 */
class Platform
{
    /**
     * 平台驱动句柄实例
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * 平台的服务实例
     * @var array
     */
    protected $services = [];

    /**
     * 架构函数
     * @access public
     * @param HandlerInterface $handler 平台驱动句柄
     * @return void
     */
    public function __construct(HandlerInterface $handler)
    {
        // 设置平台驱动句柄
        $this->handler = $handler;
    }

    /**
     * 获取服务实例
     * @access public
     * @param string $name
     * @param bool $newInstance 是否每次创建新的实例
     * @return mixed
     */
    public function service(string $name, bool $newInstance = false)
    {
        // 为空
        if (empty($name)) {
            throw new \Exception(sprintf(
                'Unable to resolve empty service for [%s].',
                static::class
            ));
        }

        // 如果服务已经存在且不需要创建新的实例
        if(isset($this->services[$name]) && !$newInstance){
            // 直接返回
            return $this->services[$name];
        }
        // 创建服务实例
        $object = $this->handler->createService($name);
        // 记录实例
        if (!$newInstance) {
            $this->services[$name] = $object;
        }
        // 返回
        return $object;
    }

	/**
     * 动态设置平台配置参数
     * @access public
     * @param array $options 平台配置
     * @return $this
     */
    public function setConfig(array $options)
    {
        // 设置句柄配置
        $this->handler->setConfig($options);
        // 返回
        return $this;
    }

    /**
     * 获取平台配置
     * @access public
     * @param null|string $name 名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        // 返回句柄配置
        return $this->handler->getConfig($name, $default);
    }

    /**
     * 动态调用
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->handler->$method(...$parameters);
    }
}