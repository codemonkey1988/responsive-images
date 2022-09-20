<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Responsive Images',
    'description' => 'Adds responsive images support for many browser types to TYPO3 using the picture tag.',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.4.99',
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
    'version' => '3.0.0-dev',
];
