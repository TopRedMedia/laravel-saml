<?php

namespace TopRedMedia\SAML;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SAMLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/topredmedia-saml.php' => config_path('topredmedia-saml.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__.'/../config/topredmedia-saml.php', 'topredmedia-saml'
        );

        // Routes
        $this->mapSAMLRoutes();
    }


    /**
     *
     */
    protected function mapSAMLRoutes()
    {
        Route::prefix(config('topredmedia-saml.route_prefix'))
            ->namespace('TopRedMedia\SAML\Controllers')
            ->group(function () {
                Route::get('{isp}/login', 'ISPController@login');
                Route::get('{isp}/logout', 'ISPController@logout');
                Route::get('{isp}/metadata', 'ISPController@metadata');
                Route::post('{isp}/acs', 'ISPController@acs');
                Route::get('{isp}/sls', 'ISPController@sls');
            });
    }
}
