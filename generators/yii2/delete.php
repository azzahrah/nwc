<?php

## load data
$data = json_decode('{{{json_data}}}');
$settings = json_decode('{{{settings}}}');

## remove nginx conf

$path = $settings->nginx_path . "/" . "sites-available" . "/" . $data->server_name;
$path_enabled = $settings->nginx_path . "/" . "sites-enabled" . "/" . $data->server_name;

if (is_file($path)) {
	unlink($path);
}
if (is_link($path_enabled)) {
	unlink($path_enabled);
}

## remove root directory
exec("rm -rf {$data->root_directory}");
exec("rm -rf {$data->git_dir}");