<?php
/*
 *   Jamshidbek Akhlidinov
 *   30 - 7 2025 17:13:19
 *   https://ustadev.uz
 *   https://github.com/JamshidbekAkhlidinov
 */

use app\models\Data;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Data $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url:url',
            'code',
            [
                'attribute' => 'qr_file',
                'format' => 'raw',
                'value' => function (Data $model) {
                    return Html::img($model->qr_file, ['width' => '300px']);
                }
            ],
            'count',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
