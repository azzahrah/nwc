<?php

## Warning, All error is ignored in this step
## Well, I'm too lazy to check it :(, will do it soon

## load data
$data = json_decode('{{{json_data}}}');
$settings = json_decode('{{{settings}}}');
$nginx = dirname(__FILE__) . "/nginx.conf";

## configure path
$sa = $settings->nginx_path. "/sites-available/" . $data->server_name;
$se = $settings->nginx_path. "/sites-enabled/" . $data->server_name;

## generate nginx config
$content = file_get_contents($nginx);
$content = strtr($content,$session_replaced);

## put generated nginx config to the right path
file_put_contents($sa , $content);
symlink($sa,$se);

## create directory for newly created vhost
mkdir($data->root_directory, 0755, true);
file_put_contents($data->root_directory . "/index.html", "Hello NWC !!");
exec('chown -R www-data:www-data ' . $data->root_directory );

exec("service nginx reload");

echo "SUCCESS";