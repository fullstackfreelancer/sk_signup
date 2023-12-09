<?php
declare(strict_types=1);
return [
    \SIMONKOEHLER\Signup\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'username' => [
                'fieldName' => 'username',
            ],
            'firstName' => [
                'fieldName' => 'first_name',
            ],
            'middleName' => [
                'fieldName' => 'middle_name',
            ],
            'lastName' => [
                'fieldName' => 'last_name',
            ],
            'txExtbaseType' => [
                'fieldName' => 'tx_extbase_type',
            ],
        ],
    ]
];
