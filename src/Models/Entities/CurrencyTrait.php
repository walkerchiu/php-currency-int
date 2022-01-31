<?php

namespace WalkerChiu\Currency\Models\Entities;

trait CurrencyTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(config('wk-core.class.currency.currency'), 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function currencies()
    {
        return $this->morphMany(config('wk-core.class.currency.currency'), 'host');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function currencyBase()
    {
        return $this->morphMany(config('wk-core.class.currency.currency'), 'host')
                    ->where('is_base', 1)
                    ->first();
    }
}
