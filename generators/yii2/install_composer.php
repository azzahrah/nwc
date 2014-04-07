<?php
exec ("curl -sS https://getcomposer.org/installer | php");
exec ("mv composer.phar /usr/local/bin/composer");

echo "SUCCESS";