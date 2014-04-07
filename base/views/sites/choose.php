<?php

use yii\helpers\Html;
use yii\widgets\ListView;
?>
<div class="site-create text-center">
<h1>Choose Site Generator</h1>
This will also install nginx or other dependencies if needed.
<hr/>
<div class="row">
	<?php echo ListView::widget([
		'dataProvider' => $dataProvider,
		'summary' => '',
		'options' => [
			'class' => 'infinite-scroll list-view'
		],
		'itemOptions' => ['class' => 'col-xs-6 col-md-3'],
		'itemView' => '_choose',
		'pager' => [
			'class' => \kop\y2sp\ScrollPager::className(),
		]
	]);
	?>	
</div>
</div>
