<?php

namespace App\Providers;

use App\Repository\BaseRepositoryInterface;
use App\Repository\DiscapacidadRepositoryInterface;
use App\Repository\EgresadoRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\DiscapacidadRepository;
use App\Repository\Eloquent\EgresadoRepository;
use App\Repository\Eloquent\EventoRepository;
use App\Repository\Eloquent\GradoRepository;
use App\Repository\EventoRepositoryInterface;
use App\Repository\GradoRepositoryInterface;
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
        $this->app->bind(EgresadoRepositoryInterface::class, EgresadoRepository::class);
        $this->app->bind(GradoRepositoryInterface::class, GradoRepository::class);
    }
}
