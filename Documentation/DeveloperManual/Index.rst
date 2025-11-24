====================
Developer Manual
====================

.. contents::
   :depth: 2
   :local:

Overview
========

This manual covers architectural and internal details for developers.

Driver Architecture
===================

The MongoDB driver consists of:

- `MongoDbDriver` — initializes Client + Database
- `MongoDbConnection` — wrapper around common operations
- `MongodbConnectionPoolAdapter` — TYPO3-facing adapter
- `MongodbConfiguration` — internal configuration container

Global Registration
===================

The driver is registered only if:

.. code-block:: php

if ($GLOBALS['TYPO3_CONF_VARS']['DRIVER']['MongoDB']) {
    ...
}

This allows MongoDB to behave like a TYPO3 database connection.

Error Handling
==============

The driver throws:

- `RuntimeException` when configuration is missing
- Exceptions on connection or ping failures

Unit Tests
==========

The extension ships with PHPUnit tests covering:

- Utility functions
- Configuration model
- Driver configuration validation
- Adapter interactions

Run:

.. code-block:: bash

   vendor/bin/phpunit

