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

namespace think;

use think\helper\Arr;
use think\payment\Platform;

/**
 * 支付扩展包
 */
class Payment extends Manager
{
	/**
     * 驱动的命名空间
     * @var string
     */
	protected $namespace = '\\think\\payment\\handler\\';

    /**
     * 创建驱动
     * @access public
     * @return Platform
     */
    protected function createDriver(string $name)
    {
        // 获取父类创建的驱动
        $handler = parent::createDriver($name);
        // 返回
        return new Platform($handler);
    }

	/**
     * 默认驱动
     * @access public
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }

	/**
     * 获取配置
     * @access public
     * @param null|string $name 配置名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig($name = null, $default = null)
    {
        // 读取指定配置
        if (!is_null($name)) {
            return $this->app->config->get('payment.' . $name, $default);
        }
        // 返回全部配置
        return $this->app->config->get('payment');
    }

	/**
     * 获取平台配置
     * @param string $platform 平台名称
     * @param null|string $name 配置名称
     * @param null|string $default 默认值
     * @return array
     */
    public function getPlatformConfig(string $platform, string $name = null, $default = null)
    {
		// 读取驱动配置文件
        if ($config = $this->getConfig('platforms.' . $platform)) {
            return Arr::get($config, $name, $default);
        }
		// 驱动不存在
        throw new \InvalidArgumentException('Platform [' . $platform . '] not found.');
    }

    /**
     * 当前平台的驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveType(string $name)
    {
        return $this->getPlatformConfig($name, 'type', 'alipay');
    }

	/**
     * 获取驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveConfig(string $name)
    {
        return $this->getPlatformConfig($name);
    }

	/**
     * 选择或者切换平台
     * @access public
     * @param string $name 平台的配置名
     * @return Platform
     */
    public function platform(string $name = null, array $options = [])
    {
        // 如果指定了自定义配置
        if(!empty($options)){
            // 创建驱动实例并设置参数
            return $this->createDriver($name)->setConfig($options);
        }
        // 返回已有驱动实例
        return $this->driver($name);
    }
}