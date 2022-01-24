<?php

return [
    /*
    |
    |--------------------------------------------------------------------------
    | Flare API key
    |--------------------------------------------------------------------------
    |
    | Specify Flare's API key below to enable error reporting to the service.
    |
    | More info: https://flareapp.io/docs/general/projects
    |
    */

    'key' => env('FLARE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Reporting Options
    |--------------------------------------------------------------------------
    |
    | These options determine which information will be transmitted to Flare.
    |
    */

    'reporting' => [
        'anonymize_ips' => false,
        'collect_git_information' => false,
        'report_queries' => false,
        'maximum_number_of_collected_queries' => 0,
        'report_query_bindings' => false,
        'report_view_data' => false,
        'grouping_type' => null,
        'report_logs' => false,
        'maximum_number_of_collected_logs' => 0,
        'censor_request_body_fields' => ['password'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting Log statements
    |--------------------------------------------------------------------------
    |
    | If this setting is `false` log statements won't be sent as events to Flare,
    | no matter which error level you specified in the Flare log channel.
    |
    */

    'send_logs_as_events' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Censor request body fields
    |--------------------------------------------------------------------------
    |
    | These fields will be censored from your request when sent to Flare. 
    |
    */
    
    'censor_request_body_fields' => ['password'],
];
