<?php

define ('DEBUG', true);

define ('DB_HOST',   'localhost');
define ('DB_NAME',   'getjurist');
define ('DB_LOGIN',  'getjurist');
//define ('DB_LOGIN',  'root');
define ('DB_PASS',   'hCbS4jMA2PNq3rvx');
//define ('DB_PASS',   'sd3bdNg7sk');

define ('CHAT_HOST',   'getjurist.host');
define ('DB_CHAT_HOST',   'localhost');
define ('DB_CHAT_NAME',   'ejabberd');
define ('DB_CHAT_LOGIN',  'ejabberd');
//define ('DB_CHAT_LOGIN',  'root');
define ('DB_CHAT_PASS',   '8O6o3G6s');
//define ('DB_CHAT_PASS',   'sd3bdNg7sk');

define ('IMG_HOST',  'http://api.getjust.host/files/images/');
define ('IMG_PATH', '../img/');

ini_set('zlib.output_compression_level', 3);
ob_start('ob_gzhandler');

header('Content-Type: application/json' );
