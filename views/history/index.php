<?php

use app\models\History;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\HistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Histories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'data_id',
                'format' => 'raw',
                'value' => function (History $model) {
                    $data = $model->data;
                    return Html::a(
                        $data->name,
                        ['data/view', 'id' => $data->id],
                    );
                }
            ],
            'ip_address',
            'created_at',

        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
