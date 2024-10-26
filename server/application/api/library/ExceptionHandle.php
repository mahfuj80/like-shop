<?php

namespace app\api\library;

use Exception;
use think\exception\Handle;

/**
 * 自定义API模块的错误显示
 */
class ExceptionHandle extends Handle
{
    protected $code;
    protected $message;
    protected $errorCode;
    public function render(Exception $e)
    {
        if (strpos(request()->baseUrl(), 'api') !== false || request()->isAjax()) {
            $data = [
                'code' => $e->getCode(),
                'msg' => lang($e->getMessage()),
                'data' => null,
            ];
            if (request()->param('dev') == 1) {
                $data['file'] = $e->getFile();
                $data['line'] = $e->getLine();
                // 开启调试才返回
                if (config('app.app_debug')) {
                    $data['trace'] = $e->getTrace();
                }
            }
            return json($data, 200);
        }
        // 不是api直接返回页面错误
        return parent::render($e);
    }
    /**
     * render方法 return $this;实现
     *
     * @return void
     * @copyright jcleng
     * @author jcleng
     */
    public function send()
    {
        return;
    }
}
