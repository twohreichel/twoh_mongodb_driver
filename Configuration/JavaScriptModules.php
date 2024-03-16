<?php

return [
    'dependencies' => ['backend'],
    'imports' => [
        '@twoh/mongodb-driver/lib/' => 'EXT:twoh_mongodb_driver/Resources/Public/JavaScript/Lib/',
        '@twoh/mongodb-driver/custom/' => 'EXT:twoh_mongodb_driver/Resources/Public/JavaScript/Custom/',
    ],
];