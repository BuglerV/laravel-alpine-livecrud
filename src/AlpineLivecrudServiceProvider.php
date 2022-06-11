<?php

namespace Buglerv\LaravelAlpineLivecrud;

use Illuminate\Support\ServiceProvider;

class AlpineLivecrudServiceProvider extends ServiceProvider
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
        $this->loads();
		
        if ($this->app->runningInConsole()) {
        }
		
        $this->publishes([
			__DIR__.'/../resources/views' => resource_path('views/vendor/livecrud')
		],'alpine-livecrud');
    }
	
   /**
    *  Все пути для пакета...
    */
    protected function loads()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livecrud');
    }
}
