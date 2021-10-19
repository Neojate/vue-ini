<?php

$options = [
    'module'    => 'Sample',
    'namespace' => 'App\Modules\Sample\Controllers'
];

Route::group($options, function() {
    Route::get('/sample', 'SampleController@index');
});


