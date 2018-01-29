<?php

$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Responsive Images',
    'description'      => 'Adds responsive images support for many browser types to TYPO3 using the picture tag.',
    'category'         => 'fe',
    'contraints'       => array(
        'depends'   => array(
            'typo3' => '7.6.0-8.7.99',
        ),
        'conflicts' => array(),
    ),
    'state'            => 'beta',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearCacheOnLoad' => true,
    'author'           => 'Tim Schreiner',
    'author_email'     => 'schreiner.tim@gmail.com',
    'author_company'   => '',
    'version'          => '1.1.0'
);
