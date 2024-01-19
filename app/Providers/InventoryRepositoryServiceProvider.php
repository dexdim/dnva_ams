<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InventoryRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\InventoryRepositoryInterface',
            'App\Repositories\InventoryRepository'
        );
    }
}
