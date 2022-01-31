<?php

namespace WalkerChiu\Currency\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class CurrencyInitializer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var String
     */
    protected $signature = 'command:CurrencyInitializer';

    /**
     * The console command description.
     *
     * @var String
     */
    protected $description = 'Initialize';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return Mixed
     */
    public function handle()
    {
        $this->call('command:CurrencyCleaner');

        $this->info('Initializing...');

        $items = config('wk-currency.initializer');
        foreach ($items as $item) {
            $data = [
                'abbreviation'  => $item['abbreviation'],
                'mark'          => $item['mark'],
                'exchange_rate' => $item['exchange_rate'],
                'is_base'       => $item['is_base'],
                'is_enabled'    => $item['is_enabled']
            ];
            $currency = App::make(config('wk-core.class.currency.currency'))::create($data);
            $currencyLang = App::make(config('wk-core.class.currency.currencyLang'))::create([
                'morph_type' => get_calss($currency),
                'morph_id'   => $currency->id,
                'code'       => 'en_us',
                'key'        => 'name',
                'value'      => $item['abbreviation'],
                'is_current' => 1
            ]);
            $currencyLang = App::make(config('wk-core.class.currency.currencyLang'))::create([
                'morph_type' => get_calss($currency),
                'morph_id'   => $currency->id,
                'code'       => 'zh_tw',
                'key'        => 'name',
                'value'      => $item['name'],
                'is_current' => 1
            ]);
        }
        $this->info(config('wk-core.table.currency.currencies') .' have been affected.');
        $this->info(config('wk-core.table.currency.currencies_lang') .' have been affected.');

        $this->info('Done!');
    }
}
