# MongoDB ConnectionPool Functions

This documentation provides an overview of the basic ConnectionPool Functions with MongoDB in PHP.

## Call ConnectionPool
You can access the ConnectionPool Object and carry out your queries using the following line of code.

namespace: `TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter`

````php
$mongodbConnectionPoolAdapter = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);
$mongodbConnectionPoolAdapter->getConnectionPool()->functionName();
````

or via DependencieInjection

````php
/**
 * @param ModuleTemplateFactory $moduleTemplateFactory
 * @param IconFactory $iconFactory
 */
public function __construct(
    protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter
) {
}

public function fooBar()
{
    $this->mongodbConnectionPoolAdapter->getConnectionPool()->functionName();
}
````

## Methods
[Here](https://www.mongodb.com/docs/manual/reference/operator/query/) you will find an overview of filter options. Here is a simple example in which the username is filtered and the permitted outputs are limited to columns.


### List Collections
To list collections from a MongoDB database, use the `listCollections`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->listCollections(
    $options
);
?>
```

### Create Collections
To create collections from a MongoDB database, use the `createCollections`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->createCollections(
    $collectionName,
    $options
);
?>
```

### Select Collection
To select a collection from a MongoDB database, use the `selectCollection`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->selectCollection(
    $collectionName,
    $options
);
?>
```

### Modify Collection
To modify a collection from a MongoDB database, use the `modifyCollection`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->modifyCollection(
    $collectionName, 
    $collectionOptions, 
    $options
);
?>
```

### Drop Collection
To drop a collection from a MongoDB database, use the `dropCollection`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->dropCollection(
    $collectionName,
    $options
);
?>
```


### Select

To query data from a MongoDB database, use the `selectDocuments`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->selectDocuments(
    $collectionName,
    $filter,
    $options
);
?>
```

### Count

To count data from a MongoDB database, use the `countDocuments`-Method.

```php
<?php
$this->mongodbConnectionPoolAdapter->getConnectionPool()->countDocuments(
    $collectionName,
    $filter,
    $options
);
?>
```

### Insert

To insert new data into a MongoDB database, use the `insertOneDocument` or `insertManyDocuments` -Method.

```php
<?php
$data = [
    'username' => 'admin' . $i,
    'email' => 'admin@example' . $i . '.com',
    'name' => 'Admin User' . $i,
    'uuid' => $uuid,
    'pageInteractions' => $userInteraction,
    'age' => 33,
    'isActive' => true
];
$this->mongodbConnectionPoolAdapter->getConnectionPool()->insertOneDocument(
    $collectionName, 
    $data
);
?>
```

### Update

To update data into a MongoDB database, use the `updateOneDocument` or `updateManyDocuments` -Method.

```php
<?php
$update = [
    'age' => 85,
    'isActive' => false
];
$filter = [
    'uuid' => 'user1',
];
$this->mongodbConnectionPoolAdapter->getConnectionPool()->updateOneDocument(
    $collectionName, 
    $filter, 
    $update,
    $options
);
?>
```

### Delete

To delete data from a MongoDB database, use the `deleteOneDocument` or `deleteManyDocuments` -Method.

```php
<?php
$filter = [
    'uuid' => '1',
];
$this->mongodbConnectionPoolAdapter->getConnectionPool()->deleteOneDocument(
    $collectionName, 
    $filter
);
?>
```

---