<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF['responsive_images'] = [
    'title' => 'Responsive Images',
    'description' => 'Adds responsive images support for many browser types to TYPO3 using the picture tag.',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
    ],
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Tim Schreiner',
    'author_email' => 'dev@tim-schreiner.de',
    'author_company' => '',
    'version' => '3.1.3',
];
