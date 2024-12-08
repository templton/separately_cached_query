<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=pg;port=5432;dbname=postgres_db',
    'username' => 'postgres_user',
    'password' => 'postgres_password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
