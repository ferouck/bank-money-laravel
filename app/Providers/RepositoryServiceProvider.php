<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\TransferUserRepositoryInterface;
use App\Repositories\TransferUserRepository;
use App\Interfaces\ExtractRepositoryInterface;
Use App\Repositories\ExtractRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TransferUserRepositoryInterface::class, TransferUserRepository::class);
        $this->app->bind(ExtractRepositoryInterface::class, ExtractRepository::class);
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
