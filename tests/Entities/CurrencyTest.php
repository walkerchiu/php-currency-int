<?php

namespace WalkerChiu\Currency;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\Currency\Models\Entities\Currency;
use WalkerChiu\Currency\Models\Entities\CurrencyLang;

class CurrencyTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\Currency\CurrencyServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Currency.
     *
     * For WalkerChiu\Currency\Models\Entities\Currency
     * 
     * @return void
     */
    public function testCurrency()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-currency.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-currency.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-currency.soft_delete', 1);

        // Give
        $record_1 = factory(Currency::class)->create();
        $record_2 = factory(Currency::class)->create();
        $record_3 = factory(Currency::class)->create(['is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Currency::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $record_2->delete();
            $records = Currency::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Currency::withTrashed()
                    ->find(2)
                    ->restore();
            $record_2 = Currency::find(2);
            $records = Currency::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, CurrencyLang::class);

        // Scope query on enabled records
            // When
            $records = Currency::ofEnabled()
                               ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Currency::ofDisabled()
                               ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
