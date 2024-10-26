<?php

namespace app\common\behavior;

use app\api\library\Reward;
use think\Exception;

/**
 * 开通vip
 */
class OpenVip
{
    //
    public function run($params)
    {
        try {
            // uid
            // level
            // log
            if (!empty($params['uid']) && !empty($params['level'])) {
                (new Reward)->vipReward($params);
            }
            return true;
        } catch (Exception $th) {
            trace("OpenVip", 'error');
            trace($th->getMessage(), 'error');
            trace($th->getFile(), 'error');
            trace($th->getLine(), 'error');
        }
    }
}
