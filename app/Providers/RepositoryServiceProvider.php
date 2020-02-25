<?php

namespace App\Providers;

use App\Repository\BaseRepositoryInterface;
use App\Repository\DiscapacidadRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\DiscapacidadRepository;
use App\Repository\Eloquent\EventoRepository;
use App\Repository\EventoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(DiscapacidadRepositoryInterface::class, DiscapacidadRepository::class);
        $this->app->bind(EventoRepositoryInterface::class, EventoRepository::class);
    }
}
