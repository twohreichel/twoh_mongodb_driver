<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Database\Driver;

use Exception;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Driver\ServerApi;
use RuntimeException;
use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;

/**
 * MongoDB Driver for TYPO3
 *
 * This driver provides MongoDB connectivity for TYPO3 applications.
 * Unlike SQL-based drivers, MongoDB is a NoSQL database and does not
 * implement the Doctrine DBAL Driver interface as it's conceptually different.
 */
class MongoDbDriver
{
    /**
     * @var Client|null
     */
    protected ?Client $client = null;

    /**
     * @var Database|null
     */
    protected ?Database $database = null;

    /**
     * @param array $params
     * @return MongoDbConnection
     */
    public function connect(
        array $params,
    ): MongoDbConnection {
        $mongodbConfiguration = $this->creatConfiguration($params);

        try {
            $this->client = new Client(
                'mongodb://' . $mongodbConfiguration->getUser() . ':' . $mongodbConfiguration->getPassword() . '@' . $mongodbConfiguration->getHost() . ':' . $mongodbConfiguration->getPort() ?? '27017',
                [],
                ['serverApi' => new ServerApi((string)ServerApi::V1)],
            );
            $this->database = $this->client->selectDatabase(
                $mongodbConfiguration->getDbname(),
            );

            // run connection test
            $this->connectionTest();

            return new MongoDbConnection(
                $this->database,
                $mongodbConfiguration,
            );
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    protected function connectionTest(): void
    {
        try {
            // Send a ping to confirm a successful connection
            $this->database->command(['ping' => 1]);
        } catch (Exception $e) {
            throw new RuntimeException('Ping MongoDB not successfully. ErrorMessage: ' . $e->getMessage());
        }
    }

    /**
     * @param array $params
     * @return MongodbConfiguration
     */
    protected function creatConfiguration(
        array $params,
    ): MongodbConfiguration {
        $mongodbConfiguration = new MongodbConfiguration();

        if ($this->isConfigurationValid($params, 'user')) {
            $mongodbConfiguration->setUser($params['user']);
        }

        if ($this->isConfigurationValid($params, 'password')) {
            $mongodbConfiguration->setPassword($params['password']);
        }

        if ($this->isConfigurationValid($params, 'host')) {
            $mongodbConfiguration->setHost($params['host']);
        }

        if ($this->isConfigurationValid($params, 'port')) {
            $mongodbConfiguration->setPort((int)$params['port']);
        }

        if ($this->isConfigurationValid($params, 'dbname')) {
            $mongodbConfiguration->setDbname($params['dbname']);
        }

        return $mongodbConfiguration;
    }

    /**
     * @param array $params
     * @param string $field
     * @return bool
     */
    public function isConfigurationValid(
        array $params,
        string $field,
    ): bool {
        if (empty($params[$field])) {
            throw new RuntimeException('[' . $field . '] Configuration is empty!');
        }

        return true;
    }

    /**
     * Returns the platform identifier for this driver
     *
     * @return string
     */
    public function getDatabasePlatform(): string
    {
        return 'mongodb';
    }

    /**
     * Get the MongoDB client instance
     *
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Get the MongoDB database instance
     *
     * @return Database|null
     */
    public function getDatabase(): ?Database
    {
        return $this->database;
    }
}
