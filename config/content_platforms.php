<?php

return [
    'content_platforms' => [
        'youtube' => [
            'oauth' => [
                'id' => env('YOUTUBE_OAUTH_ID'),
                'secret' => env('YOUTUBE_OAUTH_SECRET'),
            ],
        ],
    ],
];