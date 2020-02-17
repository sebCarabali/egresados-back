<?php

namespace App\Providers;

use App\Repository\DiscapacidadRepositoryInterface;
use App\Repository\Eloquent\DiscapacidadRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(DiscapacidadRepositoryInterface::class, DiscapacidadRepository::class);
    }
}
