<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\EditalRepository::class, \App\Repositories\EditalRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\EditalTipoRepository::class, \App\Repositories\EditalTipoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\EditalFilhoRepository::class, \App\Repositories\EditalFilhoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InstituicaoRepository::class, \App\Repositories\InstituicaoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        //:end-bindings:
    }
}
