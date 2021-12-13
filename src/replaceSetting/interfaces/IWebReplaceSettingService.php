<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigure\replaceSetting\interfaces;


use YiiHelper\services\interfaces\IService;

/**
 * 接口类: 替换配置(web，只为编辑内容提供接口输出)
 *
 * Interface IWebReplaceSettingService
 * @package YiiConfigure\replaceSetting\interfaces
 */
interface IWebReplaceSettingService extends IService
{
    /**
     * 开放状态的替换配置做成选项
     *
     * @return array
     */
    public function options(): array;

    /**
     * 开放状态的替换配置设置成默认内容
     *
     * @param array $params
     * @return bool
     */
    public function setDefault(array $params = []): bool;

    /**
     * 保存替换配置内容
     *
     * @param array $params
     * @return bool
     */
    public function save(array $params = []): bool;

    /**
     * 替换配置详情
     *
     * @param array $params
     * @return array
     */
    public function detail(array $params = []): array;
}