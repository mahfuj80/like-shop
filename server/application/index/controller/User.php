<?php

namespace app\index\controller;

use app\api\logic\LoginLogic;
use app\api\logic\PolicyLogic;
use app\common\exception\SystemException;
use app\common\model\Client_;
use app\common\server\ConfigServer;
use think\Controller;

/**
 * 用户邀请注册相关
 *
 * @copyright jcleng
 * @author jcleng
 */
class User extends Controller
{
    /**
     * 在网页进行邀请注册
     * http://www.like2.net/index/user/registerFromCode
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function registerFromCode()
    {
        $pcode = $this->request->get('pcode'); // 地址栏目的邀请码
        $durl = ConfigServer::get('app', 'line_android', ''); // app下载地址
        if ($this->request->isPost()) {
            $post = $this->request->post();
            if (empty($post['pcode'])) {
                throw new SystemException('请填写邀请码!');
            }
            $post['check_code'] = ConfigServer::get('register_setting', 'open', 0);
            $result = $this->validate($post, \app\api\validate\Register::class);
            if ($result === true) {
                $post['client'] = Client_::h5;
                $data = LoginLogic::register($post);
                if ($data) {
                    $this->success('注册成功', $durl, $data);
                }
                $this->error('获取失败', $result, 0);
            }
            $this->error($result, '', 0);
        } else {
            $this->assign('pcode', $pcode);
            $this->assign('durl', $durl);
            $this->assign('opentext1', PolicyLogic::service());
            $this->assign('opentext2', PolicyLogic::privacy());
            return $this->fetch();
        }
    }
}
