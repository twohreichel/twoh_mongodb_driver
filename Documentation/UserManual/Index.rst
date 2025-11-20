===============
User Manual v1.0.1
===============

.. contents:: Table of Contents
   :depth: 2
   :local:

Introduction
============

The *MongoDB Driver* extension extends TYPO3 with support for MongoDB connections.
It registers a custom Doctrine DBAL driver and exposes a Connection Pool Adapter
which can be used anywhere in TYPO3.

The extension also includes:

- A backend module to browse MongoDB collections
- ConnectionPool API wrapper for common operations
- A Symfony Console command to generate large test datasets

----

System Requirements
===================

- **PHP:** 8.0–8.3
- **Composer:** 2.x
- **TYPO3:** 12 LTS
- **PHP extension:** `ext-mongodb`
- **Library:** `mongodb/mongodb` >= 1.17

----

Installation
============

1. Install via Composer:

.. code-block:: bash

   composer require twoh/twoh_mongodb_driver

2. Activate the extension in the TYPO3 Extension Manager.

3. Add the database configuration to:

- `config/system/settings.php`, or
- `config/system/additional.php`

Example:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB'] = [
       'host' => getenv('TOOL_DB_HOST'),
       'dbname' => getenv('TOOL_DB_DATABASE'),
       'user' => getenv('TOOL_DB_USERNAME'),
       'password' => getenv('TOOL_DB_PASSWORD'),
       'port' => getenv('TOOL_DB_PORT'),
   ];

4. Clear caches.

----

Backend Module
==============

After installation, a new module appears under:

**System → MongoDB**

From here you can:

- Browse collections
- View record counts
- Navigate via dropdown menu
- Integrate charts (ChartJS)

----

Connection Pool Usage
=====================

You can access MongoDB in two ways.

**1. Via DI:**

.. code-block:: php

   public function __construct(
       protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter
   ) {}

   public function indexAction()
   {
       $users = $this->mongodbConnectionPoolAdapter
           ->getConnectionPool()
           ->selectDocuments('user', ['uuid' => 'user1']);

       // ...
   }

**2. Via GeneralUtility:**

.. code-block:: php

   $pool = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);
   $pool->getConnectionPool()->selectDocuments('user', []);

----

Available Methods
=================

Common database operations:

- `selectDocuments()`
- `countDocuments()`
- `insertOneDocument()`
- `insertManyDocuments()`
- `updateOneDocument()`
- `updateManyDocuments()`
- `deleteOneDocument()`
- `deleteManyDocuments()`
- `selectCollection()`
- `createCollection()`
- `dropCollection()`
- `listCollections()`

Detailed descriptions are located in the *ConnectionPool Manual*.

----

Console Command
===============

The extension includes a test data generator:

.. code-block:: bash

   ./vendor/bin/typo3 create:bigdata

It creates 1 million fake MongoDB records for load testing.

----

Troubleshooting
===============

- If connection fails, check your environment variables
- Ensure MongoDB server is accessible
- Make sure `ext-mongodb` is enabled in PHP
- Check backend logs: `var/log/typo3.log`

----

Changelog
=========

**1.0.1**
---------
- Documentation added
- Added PHPUnit setup and unit tests

**1.0.0**
---------
- Initial release
- Full MongoDB driver integration
- Backend module and console command
