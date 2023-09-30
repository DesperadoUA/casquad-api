<?php 
return [
    'CASINO' => [
        'label' => [
            'type' => 'string',
            'default' => ''
        ],
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
        'ref' => [
            'type' => 'json',
            'default' => []
        ],
        'color' => [
            'type' => 'string',
            'default' => '#D21037'
        ],
        'advantages' => [
            'type' => 'json',
            'default' => []
        ],
        'content_reviews' => [
            'type' => 'rich_text',
            'default' => ''
        ],
        'content_bonuses' => [
            'type' => 'rich_text',
            'default' => ''
        ],
        'content_payments' => [
            'type' => 'rich_text',
            'default' => ''
        ],
        'content_games' => [
            'type' => 'rich_text',
            'default' => ''
        ]
    ],
    'BONUS' => [
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
        'bonus' => [
            'type' => 'string',
            'default' => ''
        ],
        'min_deposit' => [
            'type' => 'string',
            'default' => ''
        ],
        'wagering' => [
            'type' => 'string',
            'default' => ''
        ]
    ],
    'GAME' => [
        'iframe' => [
            'type' => 'string',
            'default' => ''
        ],
        'rtp' => [
            'type' => 'string',
            'default' => ''
        ],
        'min_bid' => [
            'type' => 'string',
            'default' => ''
        ],
        'scheme' => [
            'type' => 'string',
            'default' => ''
        ],
        'lines' => [
            'type' => 'string',
            'default' => ''
        ],
        'symbols' => [
            'type' => 'json',
            'default' => []
        ],
        'screenshots' => [
            'type' => 'json',
            'default' => []
        ]
    ],
    'NEWS' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
    ],
    'VENDOR' => [
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
    ],
    'CURRENCY' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
    ],
    'LANGUAGE' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
    ],
    'PAYMENT' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
    ]
];