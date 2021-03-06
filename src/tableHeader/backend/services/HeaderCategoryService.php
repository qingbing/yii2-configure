<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigure\tableHeader\backend\services;


use Yii;
use YiiConfigure\tableHeader\backend\interfaces\IHeaderCategoryService;
use YiiConfigure\tableHeader\models\HeaderCategory;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use YiiHelper\helpers\Req;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ForbiddenHttpException;

/**
 * 逻辑类: 表头管理
 *
 * Class HeaderCategoryService
 * @package YiiConfigure\tableHeader\backend\services
 */
class HeaderCategoryService extends Service implements IHeaderCategoryService
{
    /**
     * 表头列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = HeaderCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, 'is_open');
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加表头
     *
     * @param array $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        if (!Req::getIsSuper()) {
            unset($params['is_open']);
        }
        $model = Yii::createObject(HeaderCategory::class);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表头
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
            unset($params['is_open']);
        }
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表头
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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
     * 查看表头详情
     *
     * @param array $params
     * @return mixed|HeaderCategory
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
     * @return HeaderCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): HeaderCategory
    {
        $model = HeaderCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("表头不存在");
        }
        return $model;
    }
}