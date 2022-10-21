<?php

namespace WalkerChiu\Currency\Models\Observers;

class CurrencyObserver
{
    /**
     * Handle the entity "retrieved" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function retrieved($entity)
    {
        //
    }

    /**
     * Handle the entity "creating" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function creating($entity)
    {
        //
    }

    /**
     * Handle the entity "created" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function created($entity)
    {
        //
    }

    /**
     * Handle the entity "updating" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function updating($entity)
    {
        //
    }

    /**
     * Handle the entity "updated" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function updated($entity)
    {
        //
    }

    /**
     * Handle the entity "saving" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function saving($entity)
    {
        if ($entity->is_base) {
            if ($entity->is_enabled == 0)
                return false;

            config('wk-core.class.currency.currency')
                ::withTrashed()
                ->where('id', '<>', $entity->id)
                ->where('host_type', $entity->host_type)
                ->where('host_id', $entity->host_id)
                ->update(['is_base' => 0]);
        }
    }

    /**
     * Handle the entity "saved" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function saved($entity)
    {
        //
    }

    /**
     * Handle the entity "deleting" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function deleting($entity)
    {
        if ($entity->is_base)
            return false;
    }

    /**
     * Handle the entity "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param Entity  $entity
     * @return void
     */
    public function deleted($entity)
    {
        if ($entity->isForceDeleting()) {
            $entity->langs()->withTrashed()
                            ->forceDelete();
        }

        if (
            config('wk-currency.onoff.account')
            && !empty(config('wk-core.class.account.profile'))
        ) {
            config('wk-core.class.account.profile')
                ::where('currency_id', $entity->currency_id)
                ->update(['currency_id' => null]);
        }
        if (
            config('wk-currency.onoff.site-mall')
            && !empty(config('wk-core.class.site-mall.site'))
        ) {
            config('wk-core.class.site-mall.site')
                ::where('currency_id', $entity->currency_id)
                ->update(['currency_id' => null]);
        }
        if (
            config('wk-currency.onoff.group')
            && !empty(config('wk-core.class.group.group'))
        ) {
            config('wk-core.class.group.group')
                ::where('currency_id', $entity->currency_id)
                ->update(['currency_id' => null]);
        }

        if (!config('wk-currency.soft_delete')) {
            $entity->forceDelete();
        }
    }

    /**
     * Handle the entity "restoring" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function restoring($entity)
    {
        //
    }

    /**
     * Handle the entity "restored" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function restored($entity)
    {
        //
    }
}
