<?php

namespace WalkerChiu\Currency\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class CurrencyLang extends Lang
{
    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.currency.currencies_lang');

        parent::__construct($attributes);
    }
}
