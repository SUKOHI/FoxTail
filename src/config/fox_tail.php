<?php

return [
    'history_type' => 'route',  // `route` or `uri`
    'session_key' => 'fox_tails',
    'home_tails' => ['home'],  // route or uri (Optional. When accessed this tail, session will be refreshed.),
    'stories' => [
        // In case of that a user accessed to route('home'), route('pricing') and finally route('contact')
        'how_much' => [
            'home',
            'pricing',
            'contact'
        ]
    ]
];