<?php
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
?>

<div class="center-block text-center">
<h2>Generating <?= $model->name ?></h2>
<div class="progress progress-striped active" style="width:40%;margin:20px auto;display:block;">
  <div class="progress-bar" role="progressbar" style="width: 0%"></div>
</div>
<div class="info" style="width:40%;margin:20px auto;display:block;">
	Loading...
</div>

<div class="alert alert-danger error" style="display:none;width:40%;margin:20px auto;">
	<?= Html::beginForm(['/sites/create','name'=>$model->id]); ?>
	ERROR OCCURED: <?= Html::submitButton('Fix Now'); ?>
	<pre class="error-data" style="display:block;margin-top:10px;"></pre>
	<?= Html::hiddenInput("error"); ?>
	<?= Html::endForm(); ?>
</div>
</div>

<script>
	<?php ob_start(); ?>
	var steps = <?= json_encode($model->steps) ?>;
	
	function run_step(number) {
		$(".info").text(steps[number].title);
		$(".progress-bar").css("width",((number + 1) /steps.length * 100) + "%");
		$.get("<?= Url::toRoute(["sites/generate_step","name"=>$model->id]); ?>&number=" + number, function(data) {
			if (data == "SUCCESS") {
				if (number + 1 < steps.length) {
					run_step(++number);
				} else {
					$(".progress").removeClass("progress-striped");
					$(".progress-bar").addClass("progress-bar-success");
					$(".info").html("Done ! <br/><br/> <a target='_blank' href='http://<?= $session['server_name'] ?>' class='btn btn-success'>Visit Site <i class='fa fa-arrow-right'></i></a>");
				}
			} else if (data[0] == "{") {
				$(".progress").removeClass("progress-striped");
				$(".progress-bar").addClass("progress-bar-danger");
				$(".error").show();
				$(".error-data").text(data);
				$("input[name=error]").val(data);
			} else {
				$(".progress").removeClass("progress-striped");
				$(".progress-bar").addClass("prgress-bar-danger");
				$(".error").show().css("padding","10px").html("<h3 style='margin:5px 0px;'>ERROR</h3>" +data);
			}
		});
	}
	run_step(0);
<?php
	$script = ob_get_clean();
	$this->registerJs($script, View::POS_END, 'generate_step');
?>
</script>