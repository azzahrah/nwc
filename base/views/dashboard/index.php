<?php

use app\models\Fragment;
use yii\helpers\Url;

foreach ($fragments as $fragment) {
    echo Fragment::render($fragment);
}
?>

<script>
    function reloadFragment(number) {
        $.get('<?=  Url::toRoute(['dashboard/fragment']) ?>&id=' + number, function(data) {
            $("#fragment_" + number).replaceWith(data);
        });
    }

<?php
foreach ($fragments as $fragment) {
    echo Fragment::renderJS($fragment);
}
?>
</script>