<?php

return [
    'default_connection' => 'main',

    'connections' => [
        /*'main'   => [
            'dsn'         => 'mysql:host=localhost;dbname=bitfriend;charset=utf8mb4',
            'user'        => 'bitfriend',
            'password'    => 'hCbS4jMA2PNq3rvx',
            'pdo_options' => [

            ]
        ],*/
        'main'   => [
            'dsn'         => 'mysql:host=localhost;dbname=getjurist;charset=utf8mb4',
            'user'        => 'getjurist',
            'password'    => 'hCbS4jMA2PNq3rvx',
            'pdo_options' => [

            ]
        ]
		/*'main'   => [
			'dsn'         => 'mysql:host=localhost;dbname=bitfriend;charset=utf8mb4',
            'user'        => 'root',
            'password'    => 'sd3bdNg7sk',
            'pdo_options' => [

            ]
        ]*//*,
        'jabber' => [
            'dsn'         => 'mysql:host=localhost;dbname=ejabberd;charset=utf8mb4',
            'user'        => 'root',
            'password'    => 'sd3bdNg7sk',
            'pdo_options' => [

            ]
        ]*/
    ]
];