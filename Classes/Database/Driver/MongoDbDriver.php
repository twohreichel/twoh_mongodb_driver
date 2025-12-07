<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Database\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Exception;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Driver\ServerApi;
use RuntimeException;
use TWOH\TwohMongodbDriver\Database\Connection\MongoDbConnection;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;

class MongoDbDriver implements Driver
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var Database
     */
    protected Database $database;

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
     * @return string
     */
    public function getDatabasePlatform(): string
    {
        return 'mongodb';
    }

    /**
     * @param Connection $conn
     * @param AbstractPlatform $platform
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): void {}

    /**
     * @return ExceptionConverter
     */
    public function getExceptionConverter(): ExceptionConverter {}
}
