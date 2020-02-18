<?php
//echo 'sqlite:' . __DIR__ . '/../data/base.db';exit;
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:' . __DIR__ .'/../data/base.db',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
];