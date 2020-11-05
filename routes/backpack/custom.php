<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('pledge', 'PledgeCrudController');
    Route::crud('member', 'MemberCrudController');
    Route::crud('location', 'LocationCrudController');
    Route::crud('collection', 'CollectionCrudController');
    Route::crud('leader', 'LeaderCrudController');

    Route::get('charts/dashboard', 'Charts\DashboardChartController@response')->name('charts.dashboard.index');
}); // this should be the absolute last line of this file
