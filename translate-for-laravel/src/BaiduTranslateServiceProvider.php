<?php namespace iscms\translate;
use Illuminate\Support\ServiceProvider;
class BaiduTranslateServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/baidu-translate.php' => config_path('baidu-translate.php'),
        ]);
    }


    public function register()
    {
        $this->app->bind('iscms\translate\TranslateApi', 'iscms\translate\TranslateService');
    }

    public function provides()
    {
        return ['translate'];
    }
}