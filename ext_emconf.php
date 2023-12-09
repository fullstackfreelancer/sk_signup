<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Signup',
    'description' => 'Basic frontend user signup plugin.',
    'category' => 'plugin',
    'author' => 'Simon Köhler',
    'author_email' => 'info@simonkoehler.com',
    'author_company' => 'SK',
    'state' => 'alpha',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
       'psr-4' => [
          'SIMONKOEHLER\\Signup\\' => 'Classes'
       ]
    ],
];
