<?php

namespace app\api\service;

use app\common\exception\SystemException;
use app\common\logic\AccountLogLogic;
use app\common\model\User;
use think\Db;

/**
 * 购买vip相关
 */
class Vip
{
    /**
     * 转赠积分
     *
     * @param int $uid
     * @param int $touid
     * @param int $score
     * @return void
     * @copyright jcleng
     * @author jcleng
     */
    public function sendScoreToUid($uid, $touid, $score)
    {
        if (empty($score) || $score < 0) {
            throw new SystemException('积分数量错误!');
        }
        $userinfo = \app\api\model\User::where('id', $uid)->find();
        if (empty($userinfo['user_integral']) || $userinfo['user_integral'] < 0) {
            throw new SystemException('帐户积分为0!');
        }

        Db::startTrans();
        try {
            $ret = User::where('id', $uid)
                ->where(Db::raw("user_integral - $score >= 0"))
                ->update([
                    'user_integral' => Db::raw("user_integral - $score"),
                ]);
            $ret2 = User::where('id', $touid)
                ->update([
                    'user_integral' => Db::raw("user_integral + $score"),
                ]);
            // 记录日志

            $log = AccountLogLogic::AccountRecord($uid, $score, 2, $uid, '', $uid);
            $log2 = AccountLogLogic::AccountRecord($touid, $score, 1, $uid, '', $uid);
            if (empty($ret)) {
                throw new SystemException('操作失败-1!');
            }
            if (empty($ret2)) {
                throw new SystemException('操作失败-2!');
            }
            if (empty($ret) || empty($ret2) || empty($log) || empty($log2)) {
                throw new SystemException('操作失败-100!');
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
        return true;
    }
    /**
     * 获取当前用户升级之后的等级
     *
     * @param integer $uid
     * @param integer $will_money 传入值就会相加
     * @return int
     * @copyright jcleng
     * @author jcleng
     */
    public function getUserLevel($uid, $will_money = 0)
    {
        // 获取已经通过的会员金额
        $old_money = Db::name('user_level_log')->where('uid', $uid)
            ->where('status', 1)
            ->sum('money_value');
        if (!empty($will_money)) {
            $old_money = bcadd($will_money, $old_money, 2);
        }
        $level = 1;
        if ($old_money >= 49990 && $old_money < 149990) {
            $level = 1;
        } else if ($old_money >= 149990 && $old_money < 449990) {
            $level = 2;
        } else if ($old_money >= 449990 && $old_money < 1349970) {
            $level = 3;
        } else if ($old_money >= 1349970 && $old_money < 4049900) {
            $level = 4;
        } else if ($old_money >= 4049900) {
            $level = 5;
        }
        return $level;
    }
    /**
     * 通过等级获取团队分红百分比
     *
     * @param int $level
     * @return int 20
     * @copyright jcleng
     * @author jcleng
     */
    public function levelToPoint($level)
    {
        $l = [
            '1' => 5,
            '2' => 10,
            '3' => 15,
            '4' => 20,
            '5' => 25,
        ];
        return $l[$level] ?? 0;
    }
}
