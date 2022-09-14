<?php

return [
    'class' => '\yii\mongodb\Connection',
    'dsn' => 'mongodb://localhost:27017/th',
    'options' => [
        "username" => "www",
        "password" => "check123"
    ]

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
