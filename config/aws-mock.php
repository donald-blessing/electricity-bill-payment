<?php

return [
    'sns' => [
        'topics' => [
            'bill_created'      => 'bill_created',
            'payment_completed' => 'payment_completed',
            'low_balance'       => 'low_balance',
        ],
    ],
    'sqs' => [
        'queues' => [
            'bill_notifications'    => 'bill_notifications',
            'payment_notifications' => 'payment_notifications',
            'balance_notifications' => 'balance_notifications',
        ],
    ],
];
