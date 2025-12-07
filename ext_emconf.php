<?php

$EM_CONF['twoh_mongodb_driver'] = [
    'title' => 'MongoDB Driver',
    'description' => 'Extends TYPO3 to support MongoDB.',
    'category' => 'plugin',
    'author' => 'Andreas Reichel, Igor Smertin',
    'author_email' => 'a.reichel91@outlook.com, igor.smertin@web.de',
    'author_company' => 'TWOH',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'php' => '8.2.0-8.3.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
