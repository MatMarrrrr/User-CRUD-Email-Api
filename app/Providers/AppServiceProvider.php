<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Contracts\Services\UserServiceInterface;
use App\Services\UserService;
use App\Contracts\Repositories\EmailAddressRepositoryInterface;
use App\Repositories\EmailAddressRepository;
use App\Contracts\Services\EmailAddressServiceInterface;
use App\Services\EmailAddressService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EmailAddressRepositoryInterface::class, EmailAddressRepository::class);

        // Services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(EmailAddressServiceInterface::class, EmailAddressService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
