<?php

namespace App\Providers;

use App\Domain\Interfaces\CategoriaRepositoryInterface;
use App\Domain\Interfaces\ProductoRepositoryInterface;
use App\Domain\Interfaces\StockRepositoryInterface;
use App\Domain\Interfaces\UsuarioRepositoryInterface;
use App\Domain\Interfaces\UnidadRepositoryInterface;
use App\Domain\Interfaces\SubcategoriaRepositoryInterface;
use App\Domain\Interfaces\PersonaNaturalRepositoryInterface;
use App\Domain\Interfaces\PersonaJuridicaRepositoryInterface;
use App\Domain\Interfaces\PersonaRepositoryInterface;
use App\Domain\Interfaces\VentaRepositoryInterface;
use App\Domain\Interfaces\DetalleVentaRepositoryInterface;
use App\Domain\Interfaces\CarritoRepositoryInterface;
use App\Infrastructure\Repositories\CategoriaRepository;
use App\Infrastructure\Repositories\ProductoRepository;
use App\Infrastructure\Repositories\StockRepository;
use App\Infrastructure\Repositories\UsuarioRepository;
use App\Infrastructure\Repositories\UnidadRepository;
use App\Infrastructure\Repositories\SubcategoriaRepository;
use App\Infrastructure\Repositories\PersonaNaturalRepository;
use App\Infrastructure\Repositories\PersonaJuridicaRepository;
use App\Infrastructure\Repositories\PersonaRepository;
use App\Infrastructure\Repositories\VentaRepository;
use App\Infrastructure\Repositories\DetalleVentaRepository;
use App\Infrastructure\Repositories\CarritoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to their implementations
        $this->app->bind(
            CategoriaRepositoryInterface::class,
            CategoriaRepository::class
        );
        
        $this->app->bind(
            ProductoRepositoryInterface::class,
            ProductoRepository::class
        );
        
        $this->app->bind(
            StockRepositoryInterface::class,
            StockRepository::class
        );
        
        $this->app->bind(
            UsuarioRepositoryInterface::class,
            UsuarioRepository::class
        );
        
        $this->app->bind(
            UnidadRepositoryInterface::class,
            UnidadRepository::class
        );
        
        $this->app->bind(
            SubcategoriaRepositoryInterface::class,
            SubcategoriaRepository::class
        );
        
        $this->app->bind(
            PersonaNaturalRepositoryInterface::class,
            PersonaNaturalRepository::class
        );
        
        $this->app->bind(
            PersonaJuridicaRepositoryInterface::class,
            PersonaJuridicaRepository::class
        );
        
        $this->app->bind(
            PersonaRepositoryInterface::class,
            PersonaRepository::class
        );
        
        $this->app->bind(
            VentaRepositoryInterface::class,
            VentaRepository::class
        );
        
        $this->app->bind(
            DetalleVentaRepositoryInterface::class,
            DetalleVentaRepository::class
        );
        
        $this->app->bind(
            CarritoRepositoryInterface::class,
            CarritoRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

