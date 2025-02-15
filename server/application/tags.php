<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用行为扩展定义文件

use app\common\behavior\OpenVip;

return [
    // 应用初始化
    'app_init' => [],
    // 应用开始
    'app_begin' => [],
    // 模块初始化
    'module_init' => [],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter' => [],
    // 日志写入
    'log_write' => [],
    // 应用结束
    'app_end' => [],
    //发送短信
    'sms_send' => [
        'app\\common\\behavior\\SmsSend',
    ],
    //模板消息
    'wx_message_send' => [
        'app\\common\\behavior\\WxMessageSend',
    ],
    //消息通知
    'notice' => [
        'app\\common\\behavior\\Notice',
    ],
    //足迹记录
    'footprint' => [
        'app\\common\\behavior\\Footprint',
    ],
    //取消订单后操作
    'cancel_order' => [
        'app\\common\\behavior\\CancelOrder',
    ],
    //发送短信
    'printer' => [
        'app\\common\\behavior\\Printer',
    ],
    //赠送积分或成长值
    'give_reward' => [
        'app\\common\\behavior\\GiveReward',
    ],
    // 开通vip
    'open_vip' => [
        OpenVip::class,
    ],
];
