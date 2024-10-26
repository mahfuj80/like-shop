<?php

namespace app\api\library;

use app\common\model\User;
use think\Db;

/**
 * 用过推荐等级相关
 */
class UserLevel
{
    /**
     * 用户所有下级 数量, 不含自己
     *
     * @param int $uid
     * @return int
     * @copyright jcleng
     * @author jcleng
     */
    public static function childCount($uid)
    {
        $userinfo = User::where('id', $uid)->find();
        $count = User::where('pids', 'like', $userinfo['pids'] . $userinfo['id'] . ',' . '%')
            ->count();
        return $count;
    }

    /**
     * 直系下级uid, 不含自己
     *
     * @param int $uid
     * @return array
     * @copyright jcleng
     * @author jcleng
     */
    public static function childOneIds($uid)
    {
        return User::where('first_leader', $uid)
            ->column('id');
    }
    /**
     * 所有下级用户, 不含自己
     *
     * @param int $uid
     * @return array
     * @copyright jcleng
     * @author jcleng
     */
    public static function childList($uid)
    {
        $userinfo = User::where('id', $uid)->find();
        $ids = User::where('pids', 'like', $userinfo['pids'] . $userinfo['id'] . ',' . '%')
            ->column('id');
        return $ids;
    }
    /**
     * 所有上级用户, 不含自己
     *
     * @param int $uid
     * @return array
     * @copyright jcleng
     * @author jcleng
     */
    public static function parentList($uid)
    {
        $ids = User::where('id', $uid)
            ->value('pids');
        return array_filter(explode(',', $ids));
    }
    /**
     * 获取当前用户和下级用户所有支付vip的金额
     *
     * @param int $uid
     * @return float
     * @copyright jcleng
     * @author jcleng
     */
    public static function getUserChildLevelMoney($uid)
    {
        $child = self::childList($uid);
        if (empty($child)) {
            $child = [];
        }
        $child[] = $uid;
        return Db::name('user_level_log')->where('uid', 'in', $child)
            ->where('status', 1)
            ->sum('money_value');
    }

}
