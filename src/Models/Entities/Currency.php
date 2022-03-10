<?php

namespace WalkerChiu\Currency\Models\Entities;

use WalkerChiu\Core\Models\Entities\Entity;
use WalkerChiu\Core\Models\Entities\LangTrait;

class Currency extends Entity
{
    use LangTrait;



    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.currency.currencies');

        $this->fillable = array_merge($this->fillable, [
            'host_type', 'host_id',
            'serial',
            'abbreviation', 'mark', 'exchange_rate',
            'is_base'
        ]);

        $this->casts = array_merge($this->casts, [
            'is_base' => 'boolean'
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get it's lang entity.
     *
     * @return Lang
     */
    public function lang()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-currency.onoff.core-lang_core')
        ) {
            return config('wk-core.class.core.langCore');
        } else {
            return config('wk-core.class.currency.currencyLang');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function langs()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-currency.onoff.core-lang_core')
        ) {
            return $this->langsCore();
        } else {
            return $this->hasMany(config('wk-core.class.currency.currencyLang'), 'morph_id', 'id');
        }
    }

    /**
     * Get the owning host model.
     */
    public function host()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(config('wk-core.class.account.profile'), 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sites()
    {
        return $this->hasMany(config('wk-core.class.site.site'), 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(config('wk-core.class.group.group'), 'currency_id', 'id');
    }
}
