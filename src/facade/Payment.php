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

namespace think\facade;

use think\Facade;

/**
 * @see \think\Payment
 * @mixin \think\Payment
 */
class Payment extends Facade
{
    /**
     * 获取当前Facade对应类名
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return \think\Payment::class;
    }
}