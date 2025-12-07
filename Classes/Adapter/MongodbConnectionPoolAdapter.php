<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Adapter;

use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;

class MongodbConnectionPoolAdapter
{
    public function getConnectionPool(): MongoDbConnection
    {
        return $GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']['ConnectionPool'];
    }
}
