<?php

namespace app\api\library;

use app\common\exception\SystemException;
use app\common\logic\AccountLogLogic;
use app\common\model\AccountLog;
use think\Db;

/**
 * 用户奖励相关
 */
class Reward
{

    /**
     * vip开通奖励
     *
     * @param array $prarms
     * @return bool
     * @copyright jcleng
     * @author jcleng
     */
    public function vipReward($prarms)
    {
        $uid = $prarms['uid'];
        $level = $prarms['level'];
        $log = $prarms['log'];
        // //
        // $orderinfo = Order::where('id', $order_id)->find();
        // if (empty($orderinfo['id'])) {
        //     throw new SystemException('订单不存在或者订单号错误!');
        // }
        // $uid = $orderinfo['user_id'];
        // if ($orderinfo['order_status'] != 3) {
        //     throw new SystemException('订单不可操作, 不是已完成状态!');
        // }
        $userinfo = fuserInfo($uid);
        // // T店长（秒杀专区）每单奖励10元
        // $is_sell_goods = OrderGoods::where('order_id', $order_id)
        //     ->where('is_seckill', 1)
        //     ->count();
        // if (!empty($userinfo['is_storer']) && !empty($is_sell_goods)) {
        //     // ! 奖励10元
        //     try {
        //         $source_type = AccountLog::order_store_add;
        //         $this->toStoreUserTenYuan($orderinfo['user_id'], 10, $order_id, $source_type);
        //     } catch (\Throwable $th) {
        //         trace("toStoreUserTenYuan10", 'error');
        //         trace($th->getMessage(), 'error');
        //         trace($th->getFile(), 'error');
        //         trace($th->getLine(), 'error');
        //     }
        // }
        // ! 销售奖励 1代10% 2代10% 3代12%
        try {
            if (!empty($userinfo['first_leader'])) {
                $first_leader_money = bcmul($log['money_value'], bcdiv(10, 100, 2), 2);
                $source_type = AccountLog::order_p1;
                try {
                    $this->toStoreUserTenYuan($userinfo['first_leader'], $first_leader_money, $log['id'], $source_type);
                } catch (\Throwable $th) {
                    trace("toStoreUserTenYuan1", 'error');
                    trace($th->getMessage(), 'error');
                    trace($th->getFile(), 'error');
                    trace($th->getLine(), 'error');
                    // throw $th;
                }
            }
            if (!empty($userinfo['second_leader'])) {
                $second_leader_money = bcmul($log['money_value'], bcdiv(10, 100, 2), 2);
                $source_type = AccountLog::order_p2;
                try {
                    $this->toStoreUserTenYuan($userinfo['second_leader'], $second_leader_money, $log['id'], $source_type);
                } catch (\Throwable $th) {
                    trace("toStoreUserTenYuan2", 'error');
                    trace($th->getMessage(), 'error');
                    trace($th->getFile(), 'error');
                    trace($th->getLine(), 'error');
                }
            }
            if (!empty($userinfo['third_leader'])) {
                $third_leader_money = bcmul($log['money_value'], bcdiv(12, 100, 2), 2);
                $source_type = AccountLog::order_p3;
                try {
                    $this->toStoreUserTenYuan($userinfo['third_leader'], $third_leader_money, $log['id'], $source_type);
                } catch (\Throwable $th) {
                    trace("toStoreUserTenYuan3", 'error');
                    trace($th->getMessage(), 'error');
                    trace($th->getFile(), 'error');
                    trace($th->getLine(), 'error');
                }
            }
        } catch (\Throwable $th) {
            trace("jiangli2", 'error');
            trace($th->getMessage(), 'error');
            trace($th->getFile(), 'error');
            trace($th->getLine(), 'error');
            // throw $th;
        }
        // ! 团队奖励: 只有上级满足都分别奖励全部
        $pids = UserLevel::parentList($uid);
        if (!empty($pids)) {
            // 获取对应的等级
            $userList = Db::name('user')->where('id', 'in', $pids)
                ->column('level', 'id');
            foreach ($userList as $_uid => $_level) {
                $_point = (new \app\api\service\Vip)->levelToPoint($_level);
                if (!empty($_point)) {
                    $_thisone_money = bcmul($log['money_value'], bcdiv($_point, 100, 2), 2);
                    $source_type = AccountLog::order_team;
                    try {
                        $this->toStoreUserTenYuan($_uid, $_thisone_money, time(), $source_type);
                    } catch (\Throwable $th) {
                        trace("teammoney", 'error');
                        trace($th->getMessage(), 'error');
                        trace($th->getFile(), 'error');
                        trace($th->getLine(), 'error');
                    }
                }
            }
        }
        // 3、股东分红, 单独算, 把所有大于6的人拿出来, 都奖励5%
        // ! 升级股东数据
        if (!empty($pids)) {
            try {
                foreach ($pids as $_uid) {
                    $this_sum = UserLevel::getUserChildLevelMoney($_uid);
                    if ($this_sum >= 5000000) {
                        Db::name('user')
                            ->where('is_sha', 0)
                            ->where('id', $_uid)
                            ->update([
                                'is_sha' => 1,
                            ]);
                    }
                }
            } catch (\Throwable $th) {
                // throw $th;
            }
        }
        $level_6userids = Db::name('user')
            ->where('is_sha', 1)->column('id');
        if (!empty($level_6userids)) {
            foreach ($level_6userids as $_uid) {
                $_point = 5;
                $_thisone_money = bcmul($log['money_value'], bcdiv($_point, 100, 2), 2);
                $source_type = AccountLog::order_teamall;
                try {
                    $this->toStoreUserTenYuan($_uid, $_thisone_money, time() . '_' . $_uid, $source_type);
                } catch (\Throwable $th) {
                    trace("teammoney", 'error');
                    trace($th->getMessage(), 'error');
                    trace($th->getFile(), 'error');
                    trace($th->getLine(), 'error');
                }
            }
        }
        return true;
    }
    /**
     * 直接给用户奖励金额并写到日志
     *
     * @param int $uid
     * @param float $money_yuan 金额,元
     * @return bool
     * @copyright jcleng
     * @author jcleng
     */
    function toStoreUserTenYuan($uid, $money_yuan, $order_id, $source_type)
    {
        $change_type = 1; // 1-增加；2-减少
        $source_id = $order_id; // 用来判断是否重复
        $account_log = [
            'number' => $money_yuan,
            'change_type' => $change_type,
            'source_type' => $source_type,
            // 'remark' => '',
        ];
        $check_has = AccountLog::where('user_id', $uid)
            ->where('source_type', $source_type)
            ->where('source_id', $source_id)
            ->count();
        if (!empty($check_has)) {
            return true;
        }
        Db::startTrans();
        try {
            $ret = Db::name('user')->where('id', $uid)->update([
                'user_money' => Db::raw('user_money + ' . $money_yuan),
            ]);
            if (empty($ret)) {
                throw new SystemException('操作失败-1');
            }
            $ret2 = AccountLogLogic::AccountRecord($uid, $account_log['number'], $account_log['change_type'], $account_log['source_type'], $account_log['remark'] ?? '', $source_id);
            if (empty($ret2)) {
                throw new SystemException('操作失败-2');
            }
            Db::commit();
            return true;
        } catch (\Exception $th) {
            Db::rollback();
            throw $th;
        }
    }

}
