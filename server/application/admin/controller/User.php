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

use app\admin\logic\UserLogic;
use app\common\model\User as ModelUser;

class User extends AdminBase
{
    /**
     * 会员列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $this->_success('', UserLogic::lists($get));

        }
        $this->assign('level_list', UserLogic::getLevelList());
        $this->assign('group_list', UserLogic::getGroupList());
        return $this->fetch();
    }

    /**
     * 导出
     */
    public function exportFile()
    {
        $get = $this->request->get();
        $this->_success('', UserLogic::exportFile($get));
    }

    /*
     * 设置分组
     */
    public function setGroup()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            UserLogic::setGroup($post);
            $this->_success('设置成功', '');
        }
        $this->assign('group_list', UserLogic::getGroupList());
        return $this->fetch();
    }

    /**
     * 账户调整
     * @param $id
     * @return mixed
     */
    public function adjustAccount($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $result = $this->validate($post, 'app\admin\validate\AdjustAccount');
            if ($result === true) {
                $result = UserLogic::adjustAccount($post); //逻辑层处理信息
                if ($result) {
                    $this->_success('操作成功', $result);
                }
                $result = '操作失败';
            }
            $this->_error($result);

        }
        $this->assign('info', UserLogic::getUser($id));
        return $this->fetch();
    }

    /*
     * 等级调整
     */
    public function adjustLevel($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $result = $this->validate($post, 'app\admin\validate\AdjustUserLevel');
            if ($result === true) {
                $result = UserLogic::adjustLevel($post); //逻辑层处理信息
                if ($result) {
                    $this->_success('操作成功', $result);
                }
                $result = '操作失败';
            }
            $this->_error($result);
        }
        $this->assign('info', UserLogic::getUser($id));
        $this->assign('user_level', UserLogic::getLevelList());
        return $this->fetch();

    }
    /*
     * 会员编辑
     */
    public function edit($id)
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $result = $this->validate($post, \app\admin\validate\User::class);
            if ($result === true) {
                UserLogic::edit($post);
                $this->_success('保存成功');
            }
            return $this->_error($result);
        }
        $detail = UserLogic::getUser($id, true);
        $this->assign('info', $detail);
        return $this->fetch();
    }
    /*
     * 会员详情
     */
    public function info($id)
    {
        $detail = UserLogic::getUser($id, false, true);
        $this->assign('detail', $detail);

        return $this->fetch();
    }

    public function getList()
    {
        $post = $this->request->get('');
        $list = UserLogic::getList($post);
        $this->_success('', $list);
    }
    public function sendCouponList()
    {
        if ($this->request->isAjax()) {
            $list = UserLogic::sendCouponList();
            $this->_success('', $list);
        }
        return $this->fetch();
    }

    /**
     * 转账记录
     */
    public function transferRecord()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $get['page'] = $get['page'] ?? $this->page_no;
            $get['limit'] = $get['limit'] ?? $this->page_size;
            $data = UserLogic::transferRecord($get);
            $this->_success('', $data);
        }
        return $this->fetch();
    }
    /*
     * 推荐关系
     */
    public function levelmap($id)
    {
        $ids = $this->request->get("id");
        if (empty($ids)) {
            echo '没有指定用户id';
            return;
        }
        $userinfo = ModelUser::where('id', $ids)->find();
        $childChild = ModelUser::where('pids', 'like', $userinfo['pids'] . $userinfo['id'] . ',' . '%')
            ->field("id, first_leader AS pid, nickname, mobile, level, user_money")
            ->select();
        if (!empty($userinfo['pid'])) {
            $pidinfo = ModelUser::where('id', $userinfo['pid'])->find();
        }
        $nodes = [
            [
                'id' => $userinfo['pid'] ?? 0,
                'label' => !empty($pidinfo['id']) ? (("上级: (id: " . $pidinfo['id'] . ")") . $pidinfo['nickname']) : '平台/无',
            ],
            // 自己
            [
                'id' => (int) $ids,
                "font" => ['color' => 'red'],
                'label' => '当前用户' . ("(id: " . $userinfo['id'] . ")") . $userinfo['nickname'] . 'money: ' . ($userinfo['user_money'] ?? '') . ';level: ' . ($userinfo['level'] ?? ''),
            ],
        ];
        $edges = [[
            'from' => $userinfo['pid'] ?? 0,
            'to' => $userinfo['id'],
        ]];
        if (!empty($childChild)) {
            foreach ($childChild as $_key => $value) {
                $edges[] = [
                    'from' => $value['pid'],
                    'to' => $value['id'],
                ];
                $nodes[] = [
                    'id' => $value['id'],
                    'label' => $value['nickname'] . ("(id: " . $value['id'] . ")") . 'money: ' . ($value['user_money'] ?? '') . ';level: ' . ($value['level'] ?? ''),
                ];
            }
        }
        // 所有用户
        $nodes_str = json_encode($nodes);
        $edges_str = json_encode($edges);
        // A -> B 进行抓准
        $this->assign('nodes_str', $nodes_str);
        $this->assign('edges_str', $edges_str);
        return $this->fetch();

    }
}
