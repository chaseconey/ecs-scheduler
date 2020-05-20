<?php

namespace App\Providers;

use Aws\MultiRegionClient;
use Illuminate\Support\ServiceProvider;

class EcsClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MultiRegionClient::class, function ($app) {
            return $client = new MultiRegionClient([
                'region' => 'us-west-2',
                'service' => 'ecs',
                'version' => '2014-11-13',
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
