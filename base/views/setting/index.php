<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SettingSearch $searchModel
 */
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            'key:ntext',
            'value:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'options' => ['style' => 'width:10%'],
                'buttons' => [
                    'update' => function($url, $model) {
                return Html::a("<i class='fa fa-pencil'></i> Update", $url, [ 'class' => 'btn btn-primary']);
            }]
            ],
        ],
    ]);
    ?>

</div>
