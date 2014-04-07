<?php

exec ("export DEBIAN_FRONTEND=noninteractive");
exec ("apt-get -y install python-software-properties");
exec ("add-apt-repository -y ppa:ondrej/php5");
exec ("apt-get -y update && apt-get -y upgrade");
exec ("apt-get -y install php5-fpm php5-mcrypt php5-sqlite sqlite php5-gd php5-cli php5-xcache php5-curl php5-json php5-mysql");

$php_ini_path = "/etc/php5/fpm/php.ini";
$php_ini = file_get_contents($php_ini_path);
$php_ini = str_replace(";cgi.fix_path = 1","cgi.fix_path = 0",$php_ini);
file_put_contents($php_ini_path, $php_ini);

exec("service php5-fpm restart");

echo "SUCCESS";