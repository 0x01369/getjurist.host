<?php

define ('DEBUG', true);

define ('DB_HOST',   'localhost');
define ('DB_NAME',   'myslook');
define ('DB_LOGIN',  'myslook');
//define ('DB_LOGIN',  'root');
define ('DB_PASS',   '3X1g9I7z');
//define ('DB_PASS',   'sd3bdNg7sk');

define ('CHAT_HOST',   'api.myslook.com');
define ('DB_CHAT_HOST',   'localhost');
define ('DB_CHAT_NAME',   'myslook_ejabberd');
define ('DB_CHAT_LOGIN',  'myslook_ejabberd');
//define ('DB_CHAT_LOGIN',  'root');
define ('DB_CHAT_PASS',   'C8k9V6z8');
//define ('DB_CHAT_PASS',   'sd3bdNg7sk');

define ('IMG_HOST',  'http://api.myslook.com/files/images/');
define ('IMG_PATH', '../img/');

ini_set('zlib.output_compression_level', 3);
ob_start('ob_gzhandler');

header('Content-Type: application/json' );
