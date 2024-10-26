<?php

namespace app\api\controller;

use app\api\library\UserLevel;
use app\api\model\User;
use app\common\model\Order;

/**
 * 团队
 *
 * @copyright jcleng
 * @author jcleng
 */
class TeamList extends ApiBase
{
    /**
     * 团队统计
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function teamCount()
    {
        // 直推有效
        // 团队有效
        // 小区有效
        // 团队总人数
        // 分红: 会员/推广; 累计
        $user = fuserInfo($this->user_id);
        $back_data['直推有效'] = (function () use ($user) {
            // 一级已经下单的用户
            $level1Ids = UserLevel::childOneIds($user['id']);
            if (empty($level1Ids)) {
                return 0;
            }
            $num = User::where('id', 'in', $level1Ids)
                ->count();
            return $num;
        })();
        $back_data['团队有效'] = (function () use ($user) {
            // 所有下级已经下单的用户
            $levelAllIds = UserLevel::childList($user['id']);
            if (empty($levelAllIds)) {
                return 0;
            }
            $num = User::where('id', 'in', $levelAllIds)
                ->count();
            return $num;
        })();
        $back_data['团队总人数'] = (function () use ($user) {
            // 所有下级已经下单的用户
            $levelAllIds = UserLevel::childList($user['id']);
            if (empty($levelAllIds)) {
                return 0;
            }
            return count($levelAllIds);
        })();
        $this->success('', '', $back_data);
    }
    /**
     * 团队列表
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function teamList()
    {
        $user = fuserInfo($this->user_id);
        // yiji/erji
        $type = $this->request->get('type');
        $list = User::alias('user')
            ->join('lk_user puser', 'puser.id = user.first_leader', 'LEFT');
        if ($type == 'yiji') {
            $list = $list->where('user.first_leader', $user['id']);
        }
        if ($type == 'erji') {
            $erjiuid = UserLevel::childOneIds($user['id']); // 下级用户
            if (empty($erjiuid)) {
                $erjiuid = [-1];
            }
            $allchild = UserLevel::childList($this->user_id);
            $list = $list->where('user.id', 'in', $allchild);
            $list = $list->where('user.id', 'not in', $erjiuid);
        }
        $key_words = $this->request->get('key_words');
        if (!empty($key_words)) {
            $list = $list->where('user.nickname', 'like', "$key_words%");
        }
        $list = $list
        // ->where(isVipSql(null, 'user'))
        ->field("user.nickname,user.id,user.avatar,user.mobile, user.level")
            ->paginate(20);
        if (!empty($list)) {
            $list = $list->toArray();
            $orderList = Order::where('user_id', 'in', array_column($list['data'], 'id'))
                ->group('user_id')
            // ->field('user_id, SUM(money) AS order_sum_money')
                ->select();
            // $orderList = array_column($orderList, null, 'user_id');
            // foreach ($list['data'] as &$value) {
            // $value['team_level'] = UserLevel::userTeamLevel($value['id']);
            // $value['mobile'] = mobileToStar($value['mobile']);
            // $value['is_vip'] = isVip($value['vip_type'], $value['vip_end_time']);
            // $value['order_sum_money'] = fenToYuan($orderList[$value['id']]['order_sum_money'] ?? 0);
            // // 团队总人数
            // $value['team_sum'] = (function () use ($value) {
            //     $levelAllIds = UserLevel::childList($value['id']);
            //     if (empty($levelAllIds)) {
            //         return 0;
            //     }
            //     return count($levelAllIds);
            // })();
            // // 团队订单总金额
            // $value['team_sum_ordermoney'] = (function () use ($value) {
            //     $levelAllIds = UserLevel::childList($value['id']);
            //     if (empty($levelAllIds)) {
            //         return '0.00';
            //     }
            //     $money = OrderLog::where('uid', 'in', $levelAllIds)
            //         ->where('status', 1)
            //     // ->group('uid')
            //         ->sum('money');
            //     return fenToYuan($money);
            // })();
            // }
        }
        $this->success('', '', $list);
    }
}
