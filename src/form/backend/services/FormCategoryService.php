<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigure\form\backend\services;


use Yii;
use yii\db\StaleObjectException;
use YiiConfigure\form\backend\interfaces\IFormCategoryService;
use YiiConfigure\form\models\FormCategory;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use YiiHelper\helpers\Req;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ForbiddenHttpException;

/**
 * 服务: 表单选项管理
 *
 * Class FormCategoryService
 * @package YiiConfigure\form\backend\services
 */
class FormCategoryService extends Service implements IFormCategoryService
{
    /**
     * 表单列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = FormCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['is_setting', 'is_open']);
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加表单
     *
     * @param array $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        if (!Req::getIsSuper()) {
            unset($params['is_open'], $params['is_setting']);
        }
        $model = Yii::createObject(FormCategory::class);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表单
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        if (!Req::getIsSuper()) {
            if ($model->is_open) {
                throw new ForbiddenHttpException('非超级管理员不能修改表头');
            }
            unset($params['is_open'], $params['is_setting']);
        }
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表单
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        if (!Req::getIsSuper() && $model->is_open) {
            throw new ForbiddenHttpException('非超级管理员不能修改表头');
        }
        return $model->delete();
    }

    /**
     * 查看表单详情
     *
     * @param array $params
     * @return mixed|FormCategory
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return FormCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): FormCategory
    {
        $model = FormCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("表单不存在");
        }
        return $model;
    }
}