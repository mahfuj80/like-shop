<?php

namespace app\api\controller;

use app\common\exception\SystemException;
use think\Db;

class Vip extends ApiBase
{
    /**
     * VIP购买申请
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function buyVip()
    {
        // $user = fuserInfo($this->user_id);
        $post = $this->request->post();
        if (empty($post['img'])) {
            $this->error('请上传支付凭证');
        }
        //
        do_lock_file('buyVip_' . $this->user_id, 'hold', function () use ($post) {
            $check = Db::name("user_level_log")
                ->where('uid', $this->user_id)
                ->where('status', 0)
                ->count();
            if (!empty($check)) {
                throw new SystemException('失败, 还有未审核的申请');
            }
            // 入库
            Db::name("user_level_log")
                ->insert([
                    'uid' => $this->user_id,
                    'status' => 0,
                    'img' => $post['img'],
                    'money_value' => Db::name('user_level')->where('id', 1)->value('money_value'),
                    'createtime' => date('Y-m-d H:i:s'),
                ]);
        });
        $this->_success('操作成功, 请等待审核');
        // $result = UserLogic::adjustLevel([
        //     'id' => $this->user_id,
        //     'level' => 1,
        // ]);
    }
    /**
     * 赠送积分
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function sendScoreToUid()
    {
        $user = fuserInfo($this->user_id);
        $score = $this->request->post('score');
        $touid = $this->request->post('touid');
        // 先检查是不是好友?
        if (empty($score) || $score < 0) {
            $this->error(__('请输入积分数量!'));
        }
        if ($user['id'] == $touid) {
            $this->error(__('不能给自己赠送!'));
        }
        $ret = (new \app\api\service\Vip)->sendScoreToUid($user['id'], $touid, $score);
        if (empty($ret)) {
            $this->error(__('操作失败!'));
        }
        $this->success('');
    }
}
