<?php

namespace WalkerChiu\Currency\Models\Services;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Exceptions\NotExpectedEntityException;
use WalkerChiu\Core\Models\Exceptions\NotFoundEntityException;
use WalkerChiu\Core\Models\Services\CheckExistTrait;

class CurrencyService
{
    use CheckExistTrait;

    protected $repository;
    protected $source;
    protected $target;



    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = App::make(config('wk-core.class.currency.currencyRepository'));
    }

    /*
    |--------------------------------------------------------------------------
    | Initial
    |--------------------------------------------------------------------------
    */

    /**
     * @return Entity
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Entity  $entity
     * @return void
     *
     * @throws NotExpectedEntityException
     */
    public function setSource($entity)
    {
        if (is_a($entity, config('wk-core.class.currency.currency')))
            $this->source = $entity;
        else
            throw new NotExpectedEntityException($entity);
    }

    /**
     * @return Entity
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Entity  $entity
     * @return void
     *
     * @throws NotExpectedEntityException
     */
    public function setTarget($entity)
    {
        if (is_a($entity, config('wk-core.class.currency.currency')))
            $this->target = $entity;
        else
            throw new NotExpectedEntityException($entity);
    }



    /*
    |--------------------------------------------------------------------------
    | Check Exist
    |--------------------------------------------------------------------------
    */

    /**
     * @param String  $abbreviation
     * @param Int     $id
     * @return Bool
     */
    public function checkExistAbbreviation(string $abbreviation, $id = null): bool
    {
        return $this->repository->where('abbreviation', '=', $abbreviation)
                                ->when($id, function ($query, $id) {
                                    return $query->where('id', '<>', $id);
                                  })
                                ->exists();
    }



    /*
    |--------------------------------------------------------------------------
    | Operation
    |--------------------------------------------------------------------------
    */

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @param String  $code
     * @return Array
     */
    public function getEnabledSetting(?string $host_type, ?int $host_id, string $code): array
    {
        return $this->repository->getEnabledSetting($host_type, $host_id, $code);
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @return Array
     */
    public function getEnabledSettingId($host_type = null, $host_id = null): array
    {
        return $this->repository->getEnabledSettingId($host_type, $host_id);
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @return Currency
     */
    public function getBaseSetting($host_type = null, $host_id = null)
    {
        return $this->repository->getBaseSetting($host_type, $host_id);
    }

    /**
     * @param Float  $value
     * @return Float
     *
     * @throws NotFoundEntityException
     */
    public function exchange(float $value): float
    {
        if (empty($this->source))
            throw new NotFoundEntityException($this->source);
        if (empty($this->target))
            throw new NotFoundEntityException($this->target);

        $value_base = $value / $this->source->exchange_rate;
        $value_new  = $value_base * $this->target->exchange_rate;

        return (float) $value_new;
    }
}
