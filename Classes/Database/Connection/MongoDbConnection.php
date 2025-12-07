<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Database\Connection;

use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\DeleteResult;
use MongoDB\Model\CollectionInfoIterator;
use MongoDB\UpdateResult;
use TWOH\TwohMongodbDriver\Domain\Model\MongodbConfiguration;

final class MongoDbConnection
{
    /**
     * @var Database
     */
    private Database $connection;

    /**
     * @var MongodbConfiguration
     */
    private MongodbConfiguration $mongodbConfiguration;

    /**
     * @internal The connection can be only instantiated by its driver.
     */
    public function __construct(
        Database $connection,
        MongodbConfiguration $mongodbConfiguration,
    ) {
        $this->setConnection($connection);
        $this->setMongodbConfiguration($mongodbConfiguration);
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $options
     * @return array
     */
    public function selectDocuments(
        string $collectionName,
        array $filter,
        array $options = [],
    ): array {
        return $this->getConnection()->selectCollection($collectionName)->find($filter, $options)->toArray();
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $options
     * @return int
     */
    public function countDocuments(
        string $collectionName,
        array $filter,
        array $options = [],
    ): int {
        return $this->getConnection()->selectCollection($collectionName)->countDocuments($filter, $options);
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $update
     * @param array $options
     * @return UpdateResult
     */
    public function updateOneDocument(
        string $collectionName,
        array $filter,
        array $update,
        array $options = [],
    ): UpdateResult {
        return $this->getConnection()->selectCollection($collectionName)->updateOne($filter, $update, $options);
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $update
     * @param array $options
     * @return UpdateResult
     */
    public function updateManyDocuments(
        string $collectionName,
        array $filter,
        array $update,
        array $options = [],
    ): UpdateResult {
        return $this->getConnection()->selectCollection($collectionName)->updateMany($filter, $update, $options);
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $options
     * @return DeleteResult
     */
    public function deleteOneDocument(
        string $collectionName,
        array $filter,
        array $options = [],
    ): DeleteResult {
        return $this->getConnection()->selectCollection($collectionName)->deleteOne($filter, $options);
    }

    /**
     * @param string $collectionName
     * @param array $filter
     * @param array $options
     * @return DeleteResult
     */
    public function deleteManyDocuments(
        string $collectionName,
        array $filter,
        array $options = [],
    ): DeleteResult {
        return $this->getConnection()->selectCollection($collectionName)->deleteMany($filter, $options);
    }

    /**
     * @param string $collectionName
     * @param array $data
     * @param array $options
     * @return array|mixed
     */
    public function insertOneDocument(
        string $collectionName,
        array $data,
        array $options = [],
    ): mixed {
        return $this->getConnection()->selectCollection($collectionName)->insertOne($data, $options)->getInsertedId();
    }

    /**
     * @param string $collectionName
     * @param array $data
     * @param array $options
     * @return array
     */
    public function insertManyDocuments(
        string $collectionName,
        array $data,
        array $options = [],
    ): array {
        return $this->getConnection()->selectCollection($collectionName)->insertMany($data, $options)->getInsertedIds();
    }

    /**
     * @param string $collectionName
     * @param array $options
     * @return Collection
     */
    public function selectCollection(
        string $collectionName,
        array $options = [],
    ): Collection {
        return $this->getConnection()->selectCollection($collectionName, $options);
    }

    /**
     * @param string $collectionName
     * @param array $collectionOptions
     * @param array $options
     * @return object|array
     */
    public function modifyCollection(
        string $collectionName,
        array $collectionOptions,
        array $options = [],
    ): object|array {
        return $this->getConnection()->modifyCollection($collectionName, $collectionOptions, $options);
    }

    /**
     * @param string $collectionName
     * @param array $options
     * @return object|array
     */
    public function dropCollection(
        string $collectionName,
        array $options = [],
    ): object|array {
        return $this->getConnection()->dropCollection($collectionName, $options);
    }

    /**
     * @param string $collectionName
     * @param array $options
     * @return array|object
     */
    public function createCollection(
        string $collectionName,
        array $options = [],
    ): array|object {
        return $this->getConnection()->createCollection($collectionName, $options);
    }

    /**
     * @param array $options
     * @return CollectionInfoIterator
     */
    public function listCollections(
        array $options = [],
    ): CollectionInfoIterator {
        return $this->getConnection()->listCollections($options);
    }

    public function getConnection(): Database
    {
        return $this->connection;
    }

    public function setConnection(Database $connection): void
    {
        $this->connection = $connection;
    }

    public function getMongodbConfiguration(): MongodbConfiguration
    {
        return $this->mongodbConfiguration;
    }

    public function setMongodbConfiguration(MongodbConfiguration $mongodbConfiguration): void
    {
        $this->mongodbConfiguration = $mongodbConfiguration;
    }
}
