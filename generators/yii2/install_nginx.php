<?php
$status = exec("service nginx status");
$installed = (trim($status) != "");

if (!$installed) {
	exec("export DEBIAN_FRONTEND=noninteractive");
	exec("apt-get -y install nginx");
}

echo "SUCCESS";