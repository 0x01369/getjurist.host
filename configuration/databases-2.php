<?php

return [
    'default_connection' => 'main',

    'connections' => [
        /*'main'   => [
            'dsn'         => 'mysql:host=localhost;dbname=booroom;charset=utf8mb4',
            'user'        => 'booroom',
            'password'    => 'booroom',
            'pdo_options' => [

            ]
        ],*/
        'main'   => [
            'dsn'         => 'mysql:host=localhost;dbname=myslook;charset=utf8mb4',
            'user'        => 'myslook',
            'password'    => '3X1g9I7z',
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
            'user'        => 'ejabberd',
            'password'    => 'ejabberd',
            'pdo_options' => [

            ]
        ]*/,
        'jabber' => [
            'dsn'         => 'mysql:host=localhost;dbname=myslook_ejabberd;charset=utf8mb4',
            'user'        => 'myslook_ejabberd',
            'password'    => 'C8k9V6z8',
            'pdo_options' => [

            ]
        ]
    ]
];