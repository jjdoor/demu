<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OSS\OssClient;

class AliyuncsOssServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('aliyunoss', function () {
            return new OssClient();
        });
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
