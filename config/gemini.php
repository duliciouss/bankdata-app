<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Configuration
    |--------------------------------------------------------------------------
    |
    | Kunci dan URL untuk mengakses Google Gemini API
    |
    */

    'key' => env('GEMINI_API_KEY'),

    'url' => env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent'),

];
