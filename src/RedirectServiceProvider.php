<?php
namespace Redirect;

use Illuminate\Support\ServiceProvider; 

class RedirectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    { 
        // ✅ đăng ký middleware alias  
        $this->registerRoutes();
    }
 
    private function registerRoutes()
    {
        if (!config('app.redirect_middleware_registered', true)) {
            return;
        }
        $this->loadViewsFrom(__DIR__ . '/views', 'microservice');

        $router = $this->app->make('router');
        $router->aliasMiddleware('microservice.redirect', \Microservice\Middleware\Redirect301Middleware::class);
        // ✅ CRUD quản lý redirect 301 (admin)
        $router->group(['middleware' => ['auth:web', 'keycloak-web-can'],'as' => 'microservice.', 'prefix' => env('ROUTE_PREFIX')], function () use ($router) {
            $router->resource('redirects', 'Microservices\Controllers\RedirectController');
        });
    }
}
