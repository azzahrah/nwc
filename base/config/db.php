<?php

$dir = str_replace("config","db",dirname(__FILE__));

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:'.$dir. DIRECTORY_SEPARATOR . 'data.sq3',
    'charset' => 'utf8',
];
