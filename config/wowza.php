<?php

return [
    'base_uri'    => env('WOWZA_API_URL', 'localhost:8087'),
    'digest_user' => env('WOWZA_DIGEST_USER', 'admin'),
    'digest_pass' => env('WOWZA_DIGEST_PASS', 'wowza')
];
