# MongoDB Driver

## Table of contents

- [General info](#general-info)
- [Getting started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Setup](#setup)
- [Authors](#authors)

## General info

Extends TYPO3 to support MongoDB.

## Getting started

### Prerequisites

What things you need to install the software.

* **PHP** ^8.0
* **composer** ^2
* **TYPO3** ^12

### Setup
* Install Extension via Composer or FTP!
* Activate Extension

#### Configure Connection
Add Driver Connection Settings below to your `config/system/settings.php` or `config/system/additional.php`.

````php
$GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB'] = [
    'host' => getenv('TOOL_DB_HOST'),
    'dbname' => getenv('TOOL_DB_DATABASE'),
    'user' => getenv('TOOL_DB_USERNAME'),
    'password' => getenv('TOOL_DB_PASSWORD'),
    'port' => getenv('TOOL_DB_PORT'),
];
````

#### Call ConnectionPool
You can access the ConnectionPool Object and carry out your queries using the following line of code.

namespace: `TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter`

````php
$mongodbConnectionPoolAdapter = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);
$mongodbConnectionPoolAdapter->getConnectionPool()->selectDocuments(
    $collectionName, 
    $filter,
    $options
);
````

###### Example
The Example below shows how you can take a MongoDB call and return the output into your view:

````php
/**
 * @var MongodbConnectionPoolAdapter 
 */
protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter;

/**
 * @param ModuleTemplateFactory $moduleTemplateFactory
 * @param IconFactory $iconFactory
 */
public function __construct(
    protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter
) {
}

/**
 * @param ServerRequestInterface $request
 * @param ModuleTemplate $view
 * @return ResponseInterface
*/
public function indexAction(
    ServerRequestInterface $request,
    ModuleTemplate $view
): ResponseInterface
{
    $view->assignMultiple(
        [
            'users' => $this->mongodbConnectionPoolAdapter->getConnectionPool()->selectDocuments(
                'user',
                [
                    'uuid' => 'user1',
                ],
                [
                    'limit' => 5,
                    'projection' => [
                        'uuid' => 1,
                        'username' => 1,
                        'email' => 1,
                        'name' => 1,
                        'pageInteractions' => 1,
                    ],
                ],
            )
        ]
    );
    return $view->renderResponse('AdminModule/Index');
}
````

## Charts
We integrate our charts via chartjs.org:
[Chart JS Org](https://www.chartjs.org/docs/4.4.1/getting-started/usage.html)

## ConnectionPool Functions
[ConnectionPool Functions](Documentation/ConnectionPool.md)

## Authors

- **Andreas Reichel** - _Initial work_
- **Andreas Reichel** - _Bug fixes_
- **Andreas Reichel** - _Feature development_