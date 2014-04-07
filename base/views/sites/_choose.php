<?php

use yii\helpers\Url;
?>
<a href="<?= Url::toRoute(['sites/create','name'=>$model->id]) ?>" class="text-center thumbnail">
	<div style="height:127px;border-bottom:1px solid #ddd;overflow-y:hidden;">
    <h2><?= $model->name; ?></h2>
	<?= $model->description ?>
	</div>
	<div style="border-bottom:1px solid #ddd; padding:7px 0px 10px 4px;margin-bottom:10px;">
	<?php 
		$req = explode(" ",$model->requirement); 
		foreach ($req as $r) { 
			echo '<span data-toggle="tooltip" title="'.$r.' is required" class="label label-primary">'.$r.'</span>&nbsp;';
		}
	?>
	</div>
	<div class="btn btn-success" style="margin-bottom:7px;">
		Create &nbsp;<i class="fa fa-arrow-right"></i>
	</div>
</a>