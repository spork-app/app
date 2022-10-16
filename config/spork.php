<?php

return [
    'core' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'development' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'calendar' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'greenhouse' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'news' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'finance' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'properties' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'reminders' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'research' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'garage' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'planning' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'food' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'weather' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'conditions' => ['enabled' => true, 'middleware' => ['api', 'auth:sanctum']],
    'basement' => ['enabled' => true, 'middleware' => ['api']],
];
