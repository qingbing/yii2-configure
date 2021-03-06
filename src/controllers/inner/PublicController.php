<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigure\controllers\inner;


use YiiConfigure\form\actions\ActionFormSetting;
use YiiConfigure\replaceSetting\actions\ActionReplaceSettingParse;
use YiiHelper\abstracts\RestController;

/**
 * 控制器(inner): 对内部系统提供服务
 *
 * Class PublicController
 * @package YiiConfigure\controllers\inner
 */
class PublicController extends RestController
{
    /**
     * 操作集合
     *
     * @return array
     */
    public function actions()
    {
        return [
            // 获取设置表单键值对
            'form-setting'    => ActionFormSetting::class,
            // 解析替换配置内容
            'replace-setting' => ActionReplaceSettingParse::class,
        ];
    }
}