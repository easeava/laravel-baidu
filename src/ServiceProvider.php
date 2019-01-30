<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseAva;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Foundation\Application;
use EaseBaidu\Service\SmartProgram\Application as SmartPrograme;
use EaseBaidu\Service\SmartTP\Application as SmartTP;

class ServiceProvider extends Provider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        $apps = [
            'smart_programe' => SmartPrograme::class,
            'smart_tp' => SmartTP::class,
        ];

        foreach ($apps as $key => $class) {
            if (empty(config('baidu.' . $class))) {
                continue;
            }

            // 注册默认路由
            if ($config = config('baidu.route' . $key)) {
                $this->app->router->group($config['attributes'], function ($router) use ($config) {
                    $router->post($config['uri'], $config['action']);
                });
            }

            if (! empty(config('baidu.' . $key . '.app_id'))) {
                $accounts = [
                    'default' => config('baidu.' . $key),
                ];

                config(['baidu.' . $key . 'default' => $accounts['default']]);
            } else {
                $accounts = config('baidu.' . $key);
            }

            foreach ($accounts as $account => $config) {
                $this->app->singleton('baidu.' . $key . $account, function ($app) use ($account, $config, $class) {
                    // 实例化模块
                    $easebaidu = new $class(array_merge(config('baidu.defaults', []), $config));

                    // 切换laravel cache配置
                    if (config('baidu.defaults.use_laravel_cache')) {
                        $easebaidu['cache'] = $app->cache->driver();
                    }

                    // 切换laravel request
                    $easebaidu['request'] = $app->request;

                    return $app;
                });
            }

            $this->app->alias('baidu.{$key}.default', 'baidu.' . $key);
            $this->app->alias('baidu.{$key}.default', 'easebaidu.' . $key);

            $this->app->alias('baidu.' . $key, $class);
            $this->app->alias('easebaidu.' . $key, $class);
        }
    }

    /**
     * 配置文件资源
     */
    protected function setupConfig()
    {
        $config_path = realpath(__DIR__ . '/../config/config.php');

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([
                $config_path => config_path('baidu.php'),
            ], 'laravel-baidu');
        } else {
            $this->app->configure('baidu');
        }

        $this->mergeConfigFrom($config_path, 'baidu');
    }
}