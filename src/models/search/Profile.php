<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\search;

use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;
use yii\helpers\Yii;

/**
 * Search model for current request profiling log.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Mark Jebri <mark.github@yandex.ru>
 * @since 2.0
 */
class Profile extends Base
{
    /**
     * @var string method attribute input search value
     */
    public $category;
    /**
     * @var int info attribute input search value
     */
    public $info;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'info'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category' => 'Category',
            'info' => 'Info',
        ];
    }

    /**
     * Returns data provider with filled models. Filter applied if needed.
     *
     * @param array $params an array of parameter values indexed by parameter names
     * @param array $models data to return provider for
     * @return \yii\data\ArrayDataProvider
     */
    public function search($params, $models)
    {
        $dataProvider = Yii::createObject([
            '__class' => ArrayDataProvider::class,
            'allModels' => $models,
            'pagination' => false,
            'sort' => [
                'attributes' => ['category', 'seq', 'duration', 'info'],
                'defaultOrder' => [
                    'duration' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $filter = new Filter();
        $this->addCondition($filter, 'category', true);
        $this->addCondition($filter, 'info', true);
        $dataProvider->allModels = $filter->filter($models);

        return $dataProvider;
    }
}
