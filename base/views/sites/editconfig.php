<?php 
use app\assets\AppAsset;
use yii\web\View;
use yii\helpers\Html;

$this->registerJs('
	var myCodeMirror = CodeMirror.fromTextArea($("#editor")[0], {
		lineNumbers: true,
        viewportMargin: Infinity
	});
', View::POS_END, 'codemirror');
?>

<?php $this->beginBlock('top-button'); ?>
<?= Html::a('Save', ['editconfig#'], ['class' => 'btn btn-success', 'onclick'=>'$("form").submit();return false;']) ?>
<?php 
if (isset($_POST['content'])):
	if ($error != ""): ?>
	  <button id="error" type="button" class="btn btn-danger" data-container="body" data-html="true" 
		data-toggle="popover" data-placement="bottom" data-content='<?= $error?>'>
	  <i class="fa fa-warning"></i> ERROR 
	  </button>
	<?php else: ?>
	  <button type="button" class="btn btn-primary" data-container="body" data-html="true" 
		data-toggle="popover" data-placement="bottom" data-content='Configuration is OK'>
	  <i class="fa fa-check"></i> OK
	  </button>
	<?php endif; 
endif; 
?>
<?php $this->endBlock('top-button'); ?>

<?= Html::beginForm(); ?>
<textarea name="content" id="editor"><?= $content ?></textarea>
<?= Html::endForm(); ?>
<link rel="stylesheet" href="css/codemirror.css">
<script src="js/codemirror.js"></script>
<script src="js/nginx.js"></script>