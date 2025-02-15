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

use app\admin\logic\PayConfigLogic;
use app\common\server\ConfigServer;

class PayConfig extends AdminBase
{

    /**
     * Notes: 支付列表
     * @author 段誉(2021/3/10 11:46)
     * @return mixed
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $this->_success('', PayConfigLogic::lists());
        }
        return $this->fetch();
    }

    /**
     * Notes: 余额支付
     * @author 段誉(2021/3/8 10:57)
     * @return mixed
     */
    public function editBalance()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if (empty($post['icon']) && $post['status'] == 1) {
                $this->_error('请选择支付图标');
            }
            PayConfigLogic::editBalance($post);
            $this->_success('修改成功');
        }
        $this->assign('info', PayConfigLogic::info('balance'));
        return $this->fetch();
    }

    /**
     * Notes: 编辑微信
     * @author 段誉(2021/3/8 11:16)
     * @return mixed
     */
    public function editWechat()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if ($post['status'] == 1) {
                if (empty($post['icon'])) {
                    $this->_error('请选择支付图标');
                }
                if ($post['apiclient_cert'] == '' || $post['apiclient_key'] == '') {
                    $this->_error('apiclient_cert或apiclient_key不能为空');
                }
            }
            PayConfigLogic::editWechat($post);
            $this->_success('修改成功');
        }
        $domain_name = ConfigServer::get('website', 'domain_name', '');
        $domain_name = $domain_name ? $domain_name : request()->domain();
        $this->assign('domain', $domain_name);
        $this->assign('info', PayConfigLogic::info('wechat'));
        return $this->fetch();
    }
    // 新增的线下支付
    public function editOffline()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if ($post['status'] == 1) {
                if (empty($post['icon'])) {
                    $this->_error('请选择支付图标');
                }
            }
            PayConfigLogic::editOffline($post);
            $this->_success('修改成功');
        }
        $domain_name = ConfigServer::get('website', 'domain_name', '');
        $domain_name = $domain_name ? $domain_name : request()->domain();
        $this->assign('domain', $domain_name);
        $this->assign('info', PayConfigLogic::info('offline'));
        return $this->fetch();
    }

    /**
     * Notes: 支付宝
     * @author 段誉(2021/3/10 11:47)
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editAlipay()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            if (empty($post['icon']) && $post['status'] == 1) {
                $this->_error('请选择支付图标');
            }
            PayConfigLogic::editAlipay($post);
            $this->_success('修改成功');
        }
        $this->assign('info', PayConfigLogic::info('alipay'));
        return $this->fetch();
    }

}
