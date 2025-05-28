<?php

namespace App\Providers;

use App\Services\WebShopService;
use App\Contracts\LeadviewClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LeadviewClient::class, function ($app) {
            return $app->make(WebShopService::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $webshopUrl = config('services.webshop.url');
        $webshopToken = config('services.webshop.token');
        if(!$webshopUrl || !$webshopToken) {
            throw new \Exception('Webshop URL or Token is not configured properly.');
        }

        Http::macro('leadview', function () use($webshopUrl, $webshopToken) {
            return Http::withHeaders([
                'X-SHOP-KEY' => $webshopToken,
                'Accept' => 'application/json'
            ])->baseUrl($webshopUrl);
        });
    }
}
