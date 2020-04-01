<?php

namespace Fleroviumizer\Bigcommerce;

use Illuminate\Support\ServiceProvider;

class BigcommerceServiceProvider extends ServiceProvider
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
        $this->app->bind(Bigcommerce::class, function () {
            return new Bigcommerce();
        });
    }
}
