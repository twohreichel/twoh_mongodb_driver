<?php

use TWOH\TwohMongodbDriver\Controller\AdminModuleController;

/**
 * Definitions for modules provided by EXT:twoh_mongodb_driver
 */
return [
    'admin_mongodb' => [
        'parent' => 'system',
        'position' => ['top'],
        'access' => 'admin',
        'workspaces' => 'live',
        'path' => '/module/system/mongodb',
        'labels' => 'LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang_mod.xlf',
        'extensionName' => 'twoh_mongodb_driver',
        'iconIdentifier' => 'mongodb-icon',
        'routes' => [
            '_default' => [
                'target' => AdminModuleController::class . '::handleRequest',
            ],
        ],
    ],
];