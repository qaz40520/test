<?php

namespace App\Providers;

use App\Providers\Auth\LineProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootLineSocialite();
    }

    /**
     * Import Custom Provider via boot()
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootLineSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'line',
            function ($app) use ($socialite) {
                $config = $app['config']['services.line'];
                return $socialite->buildProvider(LineProvider::class, $config);
            }
        );
    }
}
