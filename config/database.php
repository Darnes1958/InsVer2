<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlsrv'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [




      'sqlsrv' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '1433'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],
      'other' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_OTHER', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Daibany' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_DAIBANY', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],
      'BokreahAli' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_BOKREAH', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],'Elmaleh' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_ELMALEH', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],'BenTaher' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_BENTAHER', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],'Bentaher2' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_BENTAHER2', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
        // 'encrypt' => env('DB_ENCRYPT', 'yes'),
        // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
      ],'Shaheen' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_SHAHEEN', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],'Motahedon' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_Motahedon', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Malah' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_Malah', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],

      'Sohol' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_Sohol', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],

      'Safoa' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        //'database' => 'Daibany',
        'database' => env('DB_DATABASE_Safoa', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Boseed' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Boseed', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Ryada' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Ryada', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Elzawy' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Elzawy', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Mekkawi' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Mekkawi', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Madaria' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Madaria', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Boshlak' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Boshlak', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Almajd' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Almajd', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'BoshBen' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_BoshBen', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Boshlak3' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Boshlak3', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Verona' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Verona', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Elshobky' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Elshobky', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Eltaeb' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Eltaeb', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Asseel' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Asseel', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Tsaheel' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Tsaheel', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Mahary' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),

        'database' => env('DB_DATABASE_Mahary', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Elhrer' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Elhrer', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Anwar' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Anwar', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Elawamy' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Elawamy', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Aiham' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Aiham', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Raha' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Raha', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Rabeeh' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Rabeeh', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'MotTest' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_MotTest', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
      'Ateeg' => [
        'driver' => 'sqlsrv',
        'url' => env('DATABASE_URL_OTHER'),
        'host' => env('DB_HOST_OTHER', 'localhost'),
        'port' => env('DB_PORT_OTHER', '1433'),
        'database' => env('DB_DATABASE_Ateeg', 'forge'),
        'username' => env('DB_USERNAME_OTHER', 'forge'),
        'password' => env('DB_PASSWORD_OTHER', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'trust_server_certificate' => true,
      ],
        'Taleb' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL_OTHER'),
            'host' => env('DB_HOST_OTHER', 'localhost'),
            'port' => env('DB_PORT_OTHER', '1433'),
            'database' => env('DB_DATABASE_Taleb', 'forge'),
            'username' => env('DB_USERNAME_OTHER', 'forge'),
            'password' => env('DB_PASSWORD_OTHER', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'trust_server_certificate' => true,
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
