<?php

use TWOH\TwohMongodbDriver\Database\Driver\MongoDbDriver;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die('Access denied.');

// register new mongo db driver
if (isset($GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB'])) {
    /** @var MongoDbDriver $mongoDBConnectionPool */
    $mongoDBConnectionPool = GeneralUtility::makeInstance(
        MongoDbDriver::class
    );

    $GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']['ConnectionPool'] = $mongoDBConnectionPool->connect(
        $GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']
    );
}