<?php

namespace app\common\command;

use app\api\library\UserLevel;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

/**
 * 框架内单元测试命令入库
 * php think test -f test2
 * @copyright jcleng
 * @author jcleng
 */
class Test extends Command
{
    protected function configure()
    {
        $this->setName('test') // 设置Command名称
            ->addOption('function', 'f', Option::VALUE_OPTIONAL, 'function', '');
    }

    public function execute(Input $input, Output $output)
    {
        $function = $input->getOption('function');
        return $this->$function();
    }
    /**
     * 测试注册成功
     * php think test -f test2
     */
    public function test2()
    {
        // $token = '455b0afe72d9bf34565dcd1efc807dad';
        // $ret = fusertokenLogin($token);
        // dump($ret);
        // dump(fuserInfo("1"));
        // Hook::listen('goods_confirm', [
        //     'order_id' => 1
        // ]);
        // $orderinfo['goods_price'] = 100;
        // $first_leader_money = bcmul($orderinfo['goods_price'], bcdiv(10, 100, 2), 2);
        // dump($first_leader_money);
        // $order_id = 1;
        // (new \app\api\library\Reward)->goodsSales(6);
        // $userinfo['pids'] = ',1,2,3,4,';
        // $pids = implode(',', $userinfo['pids']);
        // echo Env::get('runtime_path');
        $_uid = 1;
        $ret = UserLevel::getUserChildLevelMoney($_uid);
        halt($ret);
    }
}
