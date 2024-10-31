<?php

namespace App\Providers;

use App\Services\AWS\MockSNSClient;
use App\Services\AWS\MockSQSClient;
use App\Services\AWS\SNSService;
use App\Services\AWS\SQSService;
use Illuminate\Support\ServiceProvider;

class AWSMockServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MockSNSClient::class, function () {
            return new MockSNSClient();
        });

        $this->app->singleton(MockSQSClient::class, function () {
            return new MockSQSClient();
        });

        $this->app->singleton(SNSService::class, function ($app) {
            return new SNSService($app->make(MockSNSClient::class));
        });

        $this->app->singleton(SQSService::class, function ($app) {
            return new SQSService($app->make(MockSQSClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
