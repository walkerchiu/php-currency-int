<?php

namespace WalkerChiu\Currency;

use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/currency.php' => config_path('wk-currency.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_currency_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_currency_table.php'
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-currency');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-currency'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-currency.command.cleaner'),
                config('wk-currency.command.initializer')
            ]);
        }

        config('wk-core.class.currency.currency')::observe(config('wk-core.class.currency.currencyObserver'));
        config('wk-core.class.currency.currencyLang')::observe(config('wk-core.class.currency.currencyLangObserver'));
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-currency')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/currency.php', 'wk-currency'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/currency.php', 'currency'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
