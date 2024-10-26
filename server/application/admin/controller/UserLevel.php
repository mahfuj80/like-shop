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
namespace app\admin\controller;

use app\admin\logic\UserLevelLogic;
use app\admin\logic\UserLogic;
use think\Db;
use think\facade\Hook;

class UserLevel extends AdminBase
{
    /**
     * note 会员列表
     * create_time 2020/12/15 11:45
     */
    public function lists()
    {
        $get = $this->request->get();
        if ($this->request->isAjax()) {
            $lists = UserLevelLogic::lists($get);
            $this->_success('', $lists);
        }
        return $this->fetch();
    }

    /**
     * note 添加会员
     * create_time 2020/12/15 11:45
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['del'] = 0;
            $result = $this->validate($post, 'app\admin\validate\UserLevel.add');
            if ($result === true) {
                $result = UserLevelLogic::add($post);
                if ($result) {
                    $this->_success('添加成功', '');
                }
                $result = '添加失败';
            }
            $this->_error($result);

        }
        $this->assign('privilege_list', UserLevelLogic::getPrivilegeList());
        return $this->fetch();
    }

    /**
     * note 会员编辑
     * create_time 2020/12/15 11:45
     */
    public function edit($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['del'] = 0;
            $result = $this->validate($post, 'app\admin\validate\UserLevel');
            if ($result === true) {
                $result = UserLevelLogic::edit($post);
                if ($result) {
                    $this->_success('添加成功', '');
                }
                $result = '添加失败';
            }
            $this->_error($result);

        }
        $detail = UserLevelLogic::getUserLevel($id);
        $this->assign('privilege_list', UserLevelLogic::getPrivilegeList());
        $this->assign('detail', $detail);
        return $this->fetch();
    }

    /**
     * note 会员删除
     * create_time 2020/12/15 11:44
     */
    public function del($id)
    {
        if ($this->request->isAjax()) {
            $result = $this->validate(['id' => $id], 'app\admin\validate\UserLevel.del');
            if ($result === true) {
                UserLevelLogic::del($id);
                $this->_success('删除成功', '');
            }
            $this->_error('删除失败', '');
        }
    }
    /**
     * vip申请开通列表记录
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function applayLists()
    {
        $get = $this->request->get();
        if ($this->request->isAjax()) {
            $lists = Db::name('user_level_log log')
                ->leftJoin('user', 'user.id=log.uid');
            if (isset($get['status']) && $get['status'] !== '') {
                $lists = $lists->where('status', $get['status']);
            }
            $lists = $lists->field('log.*,user.mobile,user.nickname')
                ->paginate();
            $this->_success('', $lists);
        }

        $status_list = [
            '1' => '通过',
            '-1' => '拒绝',
            '0' => '待审核',
        ];
        $this->assign('status_list', $status_list);
        return $this->fetch();
    }
    /**
     * 审核会员开通记录
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function apply()
    {
        $post = $this->request->post();
        if (empty($post['action']) || empty($post['id'])) {
            $this->_error('参数错误', '');
        }
        $log = Db::name('user_level_log')->where('id', $post['id'])->find();
        if (empty($log)) {
            $this->_error('参数错误', '');
        }
        if ($post['action'] == 1) {
            // 通过
            $_money = Db::name('user_level')->where('id', 1)->value('money_value');
            $level = (new \app\api\service\Vip)->getUserLevel($log['uid'], $_money);
            UserLogic::adjustLevel([
                'id' => $log['uid'],
                'level' => $level,
            ]);
            Hook::listen('open_vip', [
                'uid' => $log['uid'],
                'level' => $level,
                'log' => $log,
            ]);
        }
        Db::name('user_level_log')->where('id', $post['id'])
            ->update([
                'updatetime' => date('Y-m-d H:i:s'),
                'status' => $post['action'],
            ]);
        $this->_success('');
    }
}
