<?php

use app\models\Data;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DataSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Datas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'url:url',
            'code',
            [
                'attribute' => 'qr_file',
                'format' => 'raw',
                'value' => function (Data $model) {
                    return Html::img($model->qr_file, ['width' => '100px']);
                }
            ],
            'count',
            'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'visible' => !Yii::$app->user->isGuest,
                'template' => '{delete}',
                'urlCreator' => function ($action, Data $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
