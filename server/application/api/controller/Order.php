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

namespace app\api\controller;

use app\admin\logic\PayConfigLogic;
use app\api\logic\OrderLogic;
use app\common\model\Client_;
use app\common\model\Order as ModelOrder;

/**
 * 订单
 * Class Order
 * @package app\api\controller
 */
class Order extends ApiBase
{

    //订单列表
    public function lists()
    {
        $type = $this->request->get('type', 'all');
        $order_list = OrderLogic::getOrderList($this->user_id, $type, $this->page_no, $this->page_size);
        $this->_success('获取成功', $order_list);
    }

    //下单接口
    // ! 购买商品
    // ! 购买下单
    public function buy()
    {
        $post = $this->request->post();
        $post['user_id'] = $this->user_id;
        $post['client'] = $this->client;
        $check = $this->validate($post, 'app\api\validate\Order.buy');
        if (true !== $check) {
            $this->_error($check);
        }

        $action = $post['action'];
        $info = OrderLogic::info($post, $this->user_id);

        if ($info['code'] == 0) {
            $this->_error($info['msg']);
        }

        if ($this->client != Client_::pc && empty($post['pay_way'])) {
            $this->_error('请联系管理员配置支付方式');
        }

        if ($action == 'info') {
            $order = OrderLogic::add($this->user_id, $info['data'], $post);
        } else {
            // 需要lock
            $gids = array_column(empty($post['goods']) ? [] : $post['goods'], 'item_id');
            $key = 'buy_';
            if (!empty($gids)) {
                sort($gids);
                $key = 'buy_' . md5(json_encode($gids));
            }
            $order = do_lock_file($key, 'hold', function () use ($info, $post) {
                return OrderLogic::add($this->user_id, $info['data'], $post);
            });
        }
        if ($action == 'info') {
            $this->_success('', $order);
        }
        return $order;
    }

    //订单详情
    public function detail()
    {
        $order_id = $this->request->get('id');
        if (!$order_id) {
            $this->_error('请选择订单');
        }
        $order_detail = OrderLogic::getOrderDetail($order_id, $this->user_id);
        if (!$order_detail) {
            $this->_error('订单不存在了!', '');
        }
        $this->_success('获取成功', $order_detail);
    }

    //取消订单
    public function cancel()
    {
        $order_id = $this->request->post('id');
        if (empty($order_id)) {
            $this->_error('参数错误');
        }
        return OrderLogic::cancel($order_id, $this->user_id);
    }

    //删除订单
    public function del()
    {
        $order_id = $this->request->post('id');
        if (empty($order_id)) {
            $this->_error('参数错误');
        }
        return OrderLogic::del($order_id, $this->user_id);
    }

    //确认订单
    public function confirm()
    {
        $order_id = $this->request->post('id');
        if (empty($order_id)) {
            $this->_error('参数错误');
        }
        return OrderLogic::confirm($order_id, $this->user_id);
    }

    public function orderTraces()
    {
        $order_id = $this->request->get('id');
        $tips = '参数错误';
        if ($order_id) {
            $traces = OrderLogic::orderTraces($order_id, $this->user_id);
            if ($traces) {
                $this->_success('获取成功', $traces);
            }
            $tips = '暂无物流信息';
        }
        $this->_error($tips);
    }
    /**
     * 获取线下支付的银行卡信息
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function offlinePayInfo()
    {
        $this->_success('获取成功', PayConfigLogic::info('offline'));
    }
    /**
     * 线下支付上传凭证
     *
     * @return
     * @copyright jcleng
     * @author jcleng
     */
    public function updateOrderImg()
    {
        $order_id = $this->request->post('id');
        $pay_img = $this->request->post('pay_img');
        if (empty($order_id)) {
            $this->_error('id为空');
        }
        if (empty($pay_img)) {
            $this->_error('请上传凭证');
        }
        $res = ModelOrder::where('id', $order_id)
            ->where('order_status', 0)
            ->where('pay_way', 4)
            ->where('offline_status', 0)
            ->whereNull('pay_img')
            ->update([
                'pay_img' => $pay_img,
            ]);
        if (empty($res)) {
            $this->_error('操作失败!');
        }
        $this->_success('操作成功');
    }

}
