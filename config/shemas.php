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
        'content_analysis' => [
            'type' => 'rich_text',
            'default' => ''
        ],
        'content_games' => [
            'type' => 'rich_text',
            'default' => ''
        ],
        'icon' => [
            'type' => 'string',
            'default' => ''
        ],
        'wager' => [
            'type' => 'string',
            'default' => ''
        ],
        'bonus_value' => [
            'type' => 'string',
            'default' => ''
        ],
        'min_dep' => [
            'type' => 'string',
            'default' => ''
        ],
        'video' => [
            'type' => 'json',
            'default' => []
        ],
        'video_title' => [
            'type' => 'string',
            'default' => ''
        ],
        'reviews' => [
            'type' => 'json',
            'default' => []
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
        ],
        'banner' => [
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
        ],
        'game_week' => [
            'type' => 'number',
            'default' => 0
        ],
        'faq' => [
            'type' => 'json',
            'default' => []
        ],
        'ref' => [
            'type' => 'json',
            'default' => []
        ],
        'video' => [
            'type' => 'json',
            'default' => []
        ],
        'video_title' => [
            'type' => 'string',
            'default' => ''
        ],
        'slider_img' => [
            'type' => 'string',
            'default' => ''
        ],
        'reviews' => [
            'type' => 'json',
            'default' => []
        ]
    ],
    'NEWS' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
        'icon' => [
            'type' => 'string',
            'default' => ''
        ],
        'preview_img' => [
            'type' => 'string',
            'default' => ''
        ]
    ],
    'VENDOR' => [
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
        'icon' => [
            'type' => 'string',
            'default' => ''
        ],
        'banner' => [
            'type' => 'string',
            'default' => ''
        ],
        'video' => [
            'type' => 'json',
            'default' => []
        ],
        'video_title' => [
            'type' => 'string',
            'default' => ''
        ],
        'reviews' => [
            'type' => 'json',
            'default' => []
        ]
    ],
    'CURRENCY' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
    ],
    'LANGUAGE' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
    ],
    'PAYMENT' => [
        'author' => [
            'type' => 'string',
            'default' => ''
        ],
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
    ],
    'AUTHOR' => [
        'social' => [
           'type' => 'string',
           'default' => ''
        ],
    ]
];
