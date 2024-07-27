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

namespace think\payment\handler;

use think\facade\App;
use think\helper\Str;
use think\payment\contract\HandlerInterface;

/**
 * 支付平台句柄基础类
 */
abstract class Base implements HandlerInterface
{
	/**
     * 平台配置参数
     * @var array
     */
	protected $options = [];

    /**
     * 服务的命名空间
     * @var string
     */
    protected $serviceNamespace = null;

    /**
     * 架构函数
     * @access public
     * @param array $options 平台配置参数
     * @return void
     */
    public function __construct(array $options = [])
    {
        // 合并配置参数
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        // 初始化
        $this->init();
    }

	/**
     * 动态设置平台配置参数
     * @access public
     * @param array $options 平台配置
     * @return $this
     */
    public function setConfig(array $options)
    {
        // 合并配置
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        // 初始化
        $this->init();
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
        // 如果未指定则获取全部
        if(empty($name)) {
            return $this->options;
        }
        // 如果存在配置
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        // 返回默认
        return $default;
    }

	/**
     * 初始化
     * @access protected
     * @return void
     */
    protected function init()
    {
    }

    /**
     * 创建服务
     * @access public
     * @param string $name
     * @return mixed
     */
    public function createService(string $name)
    {
        // 如果命名空间为空且服务名称不带命名空间
        if (empty($this->serviceNamespace) && false === strpos($name, '\\')) {
            throw new \Exception("Service [$name] not supported.");
        }
        // 获取服务类名
        $class = false !== strpos($name, '\\') ? $name : $this->serviceNamespace . Str::studly($name);
        // 服务类不存在
        if (!class_exists($class)) {
            throw new \Exception("Service [$name] class not exists.");
        }
        // 实例化服务
        return App::invokeClass($class, [$this]);
    }
}