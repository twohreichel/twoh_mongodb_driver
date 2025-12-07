# MongoDB Driver for TYPO3

[![TYPO3 13](https://img.shields.io/badge/TYPO3-13.4-orange.svg)](https://get.typo3.org/version/13)
[![PHP](https://img.shields.io/badge/PHP-8.2--8.3-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.4-brightgreen.svg)](https://github.com/twohreichel/twoh_mongodb_driver/releases)
[![MongoDB](https://img.shields.io/badge/MongoDB-Driver-47A248.svg?logo=mongodb&logoColor=white)](https://www.mongodb.com/)
[![GitHub Issues](https://img.shields.io/github/issues/twohreichel/twoh_mongodb_driver)](https://github.com/twohreichel/twoh_mongodb_driver/issues)

> Extends TYPO3 to support MongoDB as a database backend.

---

## 📑 Table of Contents

- [General Info](#general-info)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Configuration](#configuration)
- [Usage](#usage)
  - [Basic Usage](#basic-usage)
  - [Full Example](#full-example)
- [Charts](#charts)
- [Documentation](#documentation)
- [Support](#support)
- [Authors](#authors)
- [License](#license)

---

## General Info

This TYPO3 extension provides a MongoDB driver that allows you to connect and interact with MongoDB databases directly from your TYPO3 installation.

**Keywords:** `TYPO3` `extension` `MongoDB` `driver` `database`

---

## Getting Started

### Prerequisites

| Requirement      | Version          |
|------------------|------------------|
| PHP              | >=8.2 <8.4       |
| TYPO3            | ^13.4            |
| MongoDB Library  | >=1.17 <2.0      |
| ext-mongodb      | >=1.17           |

### Installation

1. Install the extension via Composer:
   ```bash
   composer require twoh/twoh_mongodb_driver
   ```

2. Activate the extension in the TYPO3 Extension Manager.

### Configuration

Add the driver connection settings to your `config/system/settings.php` or `config/system/additional.php`:

```php
$GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB'] = [
    'host'     => getenv('TOOL_DB_HOST'),
    'dbname'   => getenv('TOOL_DB_DATABASE'),
    'user'     => getenv('TOOL_DB_USERNAME'),
    'password' => getenv('TOOL_DB_PASSWORD'),
    'port'     => getenv('TOOL_DB_PORT'),
];
```

---

## Usage

### Basic Usage

Access the ConnectionPool object to execute your queries:

**Namespace:** `TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter`

```php
$mongodbConnectionPoolAdapter = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);

$mongodbConnectionPoolAdapter->getConnectionPool()->selectDocuments(
    $collectionName, 
    $filter,
    $options
);
```

### Full Example

The following example demonstrates how to query MongoDB and return the results to your view:

```php
protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter;

public function __construct(
    protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter
) {
}

public function indexAction(
    ServerRequestInterface $request,
    ModuleTemplate $view
): ResponseInterface {
    $view->assignMultiple([
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
    ]);
    
    return $view->renderResponse('AdminModule/Index');
}
```

---

## Charts

This extension integrates charts via [Chart.js](https://www.chartjs.org/docs/4.4.1/getting-started/usage.html).

---

## Documentation

📖 **[ConnectionPool Functions](Documentation/ConnectionPool.md)** – Detailed documentation of all available ConnectionPool methods.

📚 **[Official Documentation](https://docs.typo3.org/p/twoh/twoh_mongodb_driver/main/en-us/)** – Full documentation on docs.typo3.org.

---

## Support

- 🐛 **[Report Issues](https://github.com/twohreichel/twoh_mongodb_driver/issues)** – Found a bug? Let us know!
- 📦 **[Source Code](https://github.com/twohreichel/twoh_mongodb_driver)** – View the source on GitHub.

---

## Authors

| Name | Role | Contact |
|------|------|---------|
| **Andreas Reichel** | Developer | [a.reichel91@outlook.com](mailto:a.reichel91@outlook.com) |
| **Igor Smertin** | Developer | [igor.smertin@web.de](mailto:igor.smertin@web.de) |

---

## License

This project is licensed under the **GPL-2.0-or-later** license. See the [LICENSE](LICENSE) file for details.