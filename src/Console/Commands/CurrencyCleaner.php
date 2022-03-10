<?php

namespace WalkerChiu\Currency\Console\Commands;

use WalkerChiu\Core\Console\Commands\Cleaner;

class CurrencyCleaner extends Cleaner
{
    /**
     * The name and signature of the console command.
     *
     * @var String
     */
    protected $signature = 'command:CurrencyCleaner';

    /**
     * The console command description.
     *
     * @var String
     */
    protected $description = 'Truncate tables';

    /**
     * Execute the console command.
     *
     * @return Mixed
     */
    public function handle()
    {
        parent::clean('currency');
    }
}
