<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Dashboard
     |--------------------------------------------------------------------------
     |
     | By default /lucid/dashboard is available when env('APP_DEBUG') is true.
     | If you set this value to "true" it will be always accessible even on
     | production environment.
     |
     */
    'dashboard' => null,

    /*
     |--------------------------------------------------------------------------
     | Namespaces
     |--------------------------------------------------------------------------
     |
     | By default the most namespaces are generated dynamicly. Allow for
     | overriding the base class namespaces.
     |
     */
    'namespaces' => [
        'foundation' => 'Lucid\Foundation',
        'foundation_model' => 'Illuminate\Database\Eloquent\Model',
        'foundation_job' => 'Lucid\Foundation\Job',
        'foundation_queueable_job' => 'Lucid\Foundation\QueueableJob',
        'foundation_feature' => 'Lucid\Foundation\Feature',
        'foundation_controller' => 'Lucid\Foundation\Http\Controller',
        'foundation_operation' => 'Lucid\Foundation\Operation',
        'foundation_queueable_operation' => 'Lucid\Foundation\QueueableOperation',
        'foundation_route_service_provider' => 'Lucid\Foundation\Providers\RouteServiceProvider',
    ],
];