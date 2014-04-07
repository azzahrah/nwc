<?php
use yii\helpers\Html;
use app\assets\AppAsset;

$rootPath = str_replace("/base", "", Yii::$app->basePath);

foreach ($model->js as $js) {
	$am = Yii::$app->assetManager->publish("../.." . str_replace($rootPath, "", $model->path)  . "/" . $js);
	$this->registerJsFile($am[1]);
}

foreach ($model->css as $css) {
	$am = Yii::$app->assetManager->publish("../.." . str_replace($rootPath, "", $model->path)  . "/" . $css);
	$this->registerCssFile($am[1]);
}

?>

<h1 style="margin:-10px 0px 10px 0px;"> 
Create <?= $model->name; ?>
</h1>

<div class="site-form">

    <?= Html::beginForm(['generate'],'post',['class'=>'form']); ?>
	<?php 
	echo Html::hiddenInput('id',$model->id);
	
	foreach ($model->fields as $name=>$field){
	
		$default = isset($field->default) ? $field->default : "";
		if (isset($session[$name])) {
			$default = $session[$name];
		}
		
		$err = isset($error->$name) ? 'has-error' : '';
		$errormsg = isset($error->$name) ? ' - ' . $error->$name : '';
		echo '<div class="form-group ' . $err . '">';
		echo Html::label($field->title . $errormsg ,$name, ['class'=>'control-label']);
		switch ($field->type) {
			case "textInput": 
				echo Html::textInput($name, $default, ['class'=>'form-control','id'=>$name]);
			break;
		}
		echo '<div class="help-block">'.$field->info.'</div>';
		echo '<hr/>';
		echo '</div>';
	}
	?>
    <div class="form-group">
        <?= Html::submitButton('Create &nbsp;<i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-success']) ?>
    </div>

    <?= Html::endForm(); ?>

</div>
