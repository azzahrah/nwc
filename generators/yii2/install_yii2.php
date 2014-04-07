<?php

$root_dir = rtrim("{{{root_directory}}}","/");

exec ("composer create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic " . $root_dir . "_composer");
exec ("mv {$root_dir}_composer/* {{{root_directory}}}");
exec ("rm -rf {$root_dir}_composer");
exec ("chown -R www-data:www-data {{{root_directory}}}");
shell_exec ("cd {{{root_directory}}} && git add .");
shell_exec ("cd {{{root_directory}}} && git commit -m 'initial commit'");
shell_exec ("cd {{{root_directory}}} && git push --set-upstream origin master");

echo "SUCCESS";