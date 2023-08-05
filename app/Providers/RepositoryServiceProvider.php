<?php

namespace App\Providers;

use App\Repositories\Advert\AdvertRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Authentication\AuthRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AdvertRepository::class, AdvertRepository::class);
    }
    public function boot(): void
    {
        //
    }
}
