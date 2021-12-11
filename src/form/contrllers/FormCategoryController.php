<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigure\form\controllers;


use YiiConfigure\form\interfaces\IFormCategoryService;
use YiiConfigure\form\models\FormCategory;
use YiiConfigure\form\services\FormCategoryService;
use YiiHelper\abstracts\RestController;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器: 表单类型管理
 *
 * Class FormCategoryController
 * @package YiiConfigure\form\controllers
 *
 * @property-read IFormCategoryService $service
 */
class FormCategoryController extends RestController
{
    public $serviceInterface = IFormCategoryService::class;
    public $serviceClass     = FormCategoryService::class;

    /**
     * 表单搜索列表
     *
     * @return array
     * @throws \Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'string', 'label' => '表单标记'],
            ['name', 'string', 'label' => '表单别名'],
            ['is_setting', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否配置'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否开放'],
            ['is_enable', 'in', 'range' => array_keys(TLabelEnable::enableLabels()), 'label' => '启用状态'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '表单列表');
    }

    /**
     * 添加表单
     *
     * @return array
     * @throws \Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'sort_order', 'is_setting', 'is_open', 'is_enable'], 'required'],
            ['key', 'unique', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '表单别名', 'targetClass' => FormCategory::class, 'targetAttribute' => 'name'],
            ['description', 'string', 'label' => '表单描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_setting', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否配置'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否开放'],
            ['is_enable', 'in', 'range' => array_keys(TLabelEnable::enableLabels()), 'label' => '启用状态'],
        ]);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加表单成功');
    }

    /**
     * 编辑表单
     *
     * @return array
     * @throws \Exception
     */
    public function actionEdit()
    {
        // 参数提前获取
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'sort_order', 'is_open', 'is_enable'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '表单别名', 'targetClass' => FormCategory::class, 'targetAttribute' => 'name', 'filter' => ['!=', 'key', $key]],
            ['description', 'string', 'label' => '表单描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否开放'],
            ['is_enable', 'in', 'range' => array_keys(TLabelEnable::enableLabels()), 'label' => '启用状态'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑表单成功');
    }

    /**
     * 删除表单
     *
     * @return array
     * @throws \Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除表单成功');
    }

    /**
     * 查看表单详情
     *
     * @return array
     * @throws \Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '表单详情');
    }
}