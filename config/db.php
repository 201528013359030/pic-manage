<?php
// $host = trim(exec("cat /usr/local/etc/ict2mysql.conf  |egrep ^dbhost|awk -F'=' '{print $2}'"));
// $port = trim(exec("cat /usr/local/etc/ict2mysql.conf  |egrep ^dbport|awk -F'=' '{print $2}'"));

$host = '192.168.139.87';
$port = '3306';
return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=$host;port=$port;dbname=pai",
    'username' => 'root',
//     'password' => 'psw.db7898',
		'password' => '123456',
    'charset' => 'utf8',
    'tablePrefix'=>''
];
