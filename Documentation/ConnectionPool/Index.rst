===============================
MongoDB ConnectionPool API
===============================

.. contents::
   :depth: 2
   :local:

Overview
========

This API provides a convenient wrapper around the MongoDB PHP library.
It simplifies CRUD operations and is optimized for TYPO3 usage.

Example
=======

.. code-block:: php

   $pool = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);
   $pool->getConnectionPool()->selectDocuments(
       'user',
       ['uuid' => 'user1'],
       ['limit' => 10]
   );

Methods
=======

List Collections
----------------

.. code-block:: php

   $pool->getConnectionPool()->listCollections();

Create Collection
-----------------

.. code-block:: php

   $pool->getConnectionPool()->createCollection('user');

Select Documents
----------------

.. code-block:: php

   $pool->getConnectionPool()->selectDocuments(
       'user',
       ['uuid' => 'user1'],
       ['projection' => ['uuid' => 1]]
   );

Count Documents
---------------

.. code-block:: php

   $pool->getConnectionPool()->countDocuments('user', []);

Insert
------

.. code-block:: php

   $pool->getConnectionPool()->insertOneDocument('user', $data);

Update
------

.. code-block:: php

   $pool->getConnectionPool()->updateOneDocument(
       'user',
       ['uuid' => 'user1'],
       ['$set' => ['active' => false]]
   );

Delete
------

.. code-block:: php

   $pool->getConnectionPool()->deleteOneDocument('user', ['uuid' => 'user1']);

