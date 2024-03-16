<?php

use TWOH\TwohMongodbDriver\Controller\AdminModuleController;

return [
    'browse_collection' => [
        'path' => '/module/system/mongodb/browse/collection/{collectionName}',
        'target' => AdminModuleController::class . '::handleRequest',
    ],
];