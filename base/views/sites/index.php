<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SiteSearch $searchModel
 */
$this->title = 'Sites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <?php $this->beginBlock('top-button'); ?>
    <?= Html::a('Create Site', ['choose'], ['class' => 'btn btn-success']) ?>
    <?php $this->endBlock('top-button'); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            'name',
            [
            	'attribute' => 'path', 
            	'header' => 'Config Path'
            ],
            [
				'attribute'=>'enabled',
				'format' => 'html',
				'value' => function($model, $index, $widget) {
					if ($model->enabled) {
						return '<div class="label label-success">Yes</div>';
					} else {
						return '<div class="label label-danger">No</div>';
					}
				}
			],
			[
				'header'=>'Action',
				'format'=>'raw',
				'value' => function($model, $index, $widget) {
					if ($model->enabled) {
						$html = Html::a("<i class='fa fa-ban'></i> &nbsp; Disable",["/sites/disable","name"=>$model->name],[
							'class' => 'btn btn-warning btn-xs'
						]);
					} else {
						$html = Html::a("<i class='fa fa-check'></i> &nbsp; Enable",["/sites/enable","name"=>$model->name],[
							'class' => 'btn btn-success btn-xs'
						]);
					}
					
					$html .= " " . Html::a("<i class='fa fa-pencil'></i> &nbsp; Edit Config",["/sites/editconfig","name"=>$model->name],[
							'class' => 'btn btn-primary btn-xs'
						]);
					

					$html .= " <a class='btn btn-danger btn-xs' onclick='return (prompt(\"TO CONFIRM TYPE: `DELETE` (without quote)\") == \"DELETE\")'
								  href='".Url::toRoute(["/sites/delete","name"=>$model->name])."'>
								<i class='fa fa-times-circle'></i> &nbsp; Delete Site</a>";
							

					return $html;
				}
			]
        ],
    ]);
    ?>

</div>
