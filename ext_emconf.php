<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Responsive Images',
    'description' => 'Adds responsive images support for many browser types to TYPO3 using the picture tag.',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
        ],
        'conflicts' => [],
    ],
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => true,
    'author' => 'Tim Schreiner',
    'author_email' => 'schreiner.tim@gmail.com',
    'author_company' => '',
    'version' => '2.2.3',
];
