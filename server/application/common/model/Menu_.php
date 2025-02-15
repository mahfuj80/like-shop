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
namespace app\common\model;

class Menu_
{
    /*
     * 首页菜单
     */
    const index_seckill = 1;
    const index_team_activity = 2;
    const index_hot_sell = 3;
    const index_coupon_list = 4;
    const index_sign_in = 5;
    const index_member_centre = 6;
    const index_my_collect = 7;
    const index_store_news = 8;
    const index_store_help = 9;
    const index_delivery_address = 10;
    const index_goods_category = 11;
    const index_luckdraw = 12;
    const index_bargain = 13;
    /*
     * 个人中心菜单
     */
    const centre_my_wallet = 10;
    const centre_generalize = 11;
    const centre_my_coupon = 12;
    const centre_level_serve = 13;
    const centre_store_help = 14;
    const centre_delivery_address = 15;
    const centre_my_collect = 16;
    const centre_service = 17;
    const centre_team_activity = 18;
    const centre_bargain = 19;
    const centre_team = 20;
    const centre_invite = 21;
    const centre_user_log = 22;
    const fenlei1 = 51;
    const fenlei2 = 52;
    const fenlei3 = 53;
    const fenlei16 = 516;
    const fenlei17 = 517;
    const fenlei18 = 518;
    const fenlei19 = 519;
    const fenlei20 = 520;
    const fenlei21 = 521;
    const fenlei22 = 522;
    const fenlei23 = 523;
    const fenlei24 = 524;

    /**
     * Notes: 菜单内容
     * @param bool $scene 场景：1-首页导航；2-个人中心
     * @param bool $from 菜单来源：获取具体的某个菜单
     * @return array
     * name         => '菜单名称'
     * link         => 调整链接
     * is_tab       => 是否的tab页
     * link_type    => 菜单类型：1-跳转；2-web-view；3-按钮（微信小程序可调用客服）
     * menu_type    => 菜单内容类型：1-正常内容；2-分销菜单
     *
     */
    public static function getMenuContent($scene = true, $from = true)
    {
        //首页菜单
        $config1 = [
            self::index_seckill => [
                'name' => '限时秒杀',
                'link' => '/pages/bundle/goods_seckill/goods_seckill',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_team_activity => [
                'name' => '拼团活动',
                'link' => '/pages/bundle/goods_combination/goods_combination',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_hot_sell => [
                'name' => '热销榜单',
                'link' => '/pages/bundle/hot_list/hot_list',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_coupon_list => [
                'name' => '领券中心',
                'link' => '/pages/user_getcoupon/user_getcoupon',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_sign_in => [
                'name' => '积分签到',
                'link' => '/pages/bundle/user_sign/user_sign',
                'is_tab' => 0,
                'menu_type' => 1,
            ],
            self::index_member_centre => [
                'name' => '会员中心',
                'link' => '/pages/user_vip/user_vip',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_my_collect => [
                'name' => '我的收藏',
                'link' => '/pages/user_collection/user_collection',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_store_news => [
                'name' => '商城资讯',
                'link' => '/pages/news_list/news_list',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_store_help => [
                'name' => '帮助中心',
                'link' => '/pages/news_list/news_list?type=1',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_delivery_address => [
                'name' => '收货地址',
                'link' => '/pages/user_address/user_address',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_goods_category => [
                'name' => '商品分类',
                'link' => '/pages/sort/sort',
                'is_tab' => 1,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_luckdraw => [
                'name' => '积分抽奖',
                'link' => '/pages/bundle/luckly_wheel/luckly_wheel',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::index_bargain => [
                'name' => '砍价活动',
                'link' => '/pages/bundle/bargain/bargain',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei1 => [
                'name' => self::fenlei1,
                'link' => '/pages/sort/sortpage?category_id=1',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei2 => [
                'name' => self::fenlei2,
                'link' => '/pages/sort/sortpage?category_id=2',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei3 => [
                'name' => self::fenlei3,
                'link' => '/pages/sort/sortpage?category_id=3',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei16 => [
                'name' => self::fenlei16,
                'link' => '/pages/sort/sortpage?category_id=16',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei17 => [
                'name' => self::fenlei17,
                'link' => '/pages/sort/sortpage?category_id=17',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei18 => [
                'name' => self::fenlei18,
                'link' => '/pages/sort/sortpage?category_id=18',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei19 => [
                'name' => self::fenlei19,
                'link' => '/pages/sort/sortpage?category_id=19',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei20 => [
                'name' => self::fenlei20,
                'link' => '/pages/sort/sortpage?category_id=20',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei21 => [
                'name' => self::fenlei21,
                'link' => '/pages/sort/sortpage?category_id=21',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei22 => [
                'name' => self::fenlei22,
                'link' => '/pages/sort/sortpage?category_id=22',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei23 => [
                'name' => self::fenlei23,
                'link' => '/pages/sort/sortpage?category_id=23',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::fenlei24 => [
                'name' => self::fenlei24,
                'link' => '/pages/sort/sortpage?category_id=24',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
        ];
        //个人中心菜单
        $config2 = [
            self::centre_my_wallet => [
                'name' => '我的钱包',
                'link' => '/pages/bundle/user_wallet/user_wallet',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_generalize => [
                'name' => '分销推广',
                'link' => '/pages/bundle/user_spread/user_spread',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 2,
            ],
            self::centre_my_coupon => [
                'name' => '我的优惠券',
                'link' => '/pages/user_coupon/user_coupon',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_level_serve => [
                'name' => '等级服务',
                'link' => '/pages/user_vip/user_vip',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_store_help => [
                'name' => '帮助中心',
                'link' => '/pages/news_list/news_list?type=1',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_delivery_address => [
                'name' => '收货地址',
                'link' => '/pages/user_address/user_address',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_my_collect => [
                'name' => '我的收藏',
                'link' => '/pages/user_collection/user_collection',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_service => [
                'name' => '联系客服',
                'link' => '/pages/bundle/contact_offical/contact_offical',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_team_activity => [
                'name' => '我的拼团',
                'link' => '/pages/bundle/user_group/user_group',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_bargain => [
                'name' => '砍价记录',
                'link' => '/pages/bundle/bargain_code/bargain_code',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_team => [
                'name' => '团队',
                'link' => '/pages/user/team/team',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_invite => [
                'name' => '邀请好友',
                'link' => '/pages/user/invite/index',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],
            self::centre_user_log => [
                'name' => '销售奖励',
                'link' => '/pages/bundle/user_bill/user_bill?type=0',
                'is_tab' => 0,
                'link_type' => 1,
                'menu_type' => 1,
            ],

        ];
        $config_name = 'config' . $scene;
        $content = $$config_name;
        if ($scene === true) {
            $content = array_merge($config1, $config2);
        }
        if ($from === true) {
            return $content;
        }
        return $content[$from] ?? [];
    }

}
