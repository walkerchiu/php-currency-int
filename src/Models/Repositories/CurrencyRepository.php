<?php

namespace WalkerChiu\Currency\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormHasHostTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryHasHostTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class CurrencyRepository extends Repository
{
    use FormHasHostTrait;
    use RepositoryHasHostTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.currency.currency'));
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @param String  $code
     * @param Array   $data
     * @param Bool    $is_enabled
     * @param String  $target
     * @param Bool    $target_is_enabled
     * @param Bool    $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(?string $host_type, ?int $host_id, string $code, array $data, $is_enabled = null, $target = null, $target_is_enabled = null, $auto_packing = false)
    {
        if (
            empty($host_type)
            || empty($host_id)
        ) {
            $instance = $this->instance;
        } else {
            $instance = $this->baseQueryForRepository($host_type, $host_id, $target, $target_is_enabled);
        }
        if ($is_enabled === true)      $instance = $instance->ofEnabled();
        elseif ($is_enabled === false) $instance = $instance->ofDisabled();

        $data = array_map('trim', $data);
        $repository = $instance->with(['langs' => function ($query) use ($code) {
                                    $query->ofCurrent()
                                          ->ofCode($code);
                                }])
                                ->whereHas('langs', function ($query) use ($code) {
                                    return $query->ofCurrent()
                                                 ->ofCode($code);
                                })
                                ->when($data, function ($query, $data) {
                                    return $query->unless(empty($data['id']), function ($query) use ($data) {
                                                return $query->where('id', $data['id']);
                                            })
                                            ->unless(empty($data['serial']), function ($query) use ($data) {
                                                return $query->where('serial', $data['serial']);
                                            })
                                            ->unless(empty($data['abbreviation']), function ($query) use ($data) {
                                                return $query->where('abbreviation', $data['abbreviation']);
                                            })
                                            ->unless(empty($data['mark']), function ($query) use ($data) {
                                                return $query->where('mark', $data['mark']);
                                            })
                                            ->unless(empty($data['exchange_rate']), function ($query) use ($data) {
                                                return $query->where('exchange_rate', $data['exchange_rate']);
                                            })
                                            ->when(isset($data['is_base']), function ($query) use ($data) {
                                                return $query->where('is_base', $data['is_base']);
                                            })
                                            ->unless(empty($data['name']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'name')
                                                          ->where('value', 'LIKE', "%".$data['name']."%");
                                                });
                                            })
                                            ->unless(empty($data['description']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'description')
                                                          ->where('value', 'LIKE', "%".$data['description']."%");
                                                });
                                            })
                                            ->unless(empty($data['remarks']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'remarks')
                                                          ->where('value', 'LIKE', "%".$data['remarks']."%");
                                                });
                                            });
                                })
                                ->orderBy('updated_at', 'DESC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-currency.output_format'), config('wk-currency.pagination.pageName'), config('wk-currency.pagination.perPage'));
            $factory->setFieldsLang(['name', 'description', 'remarks']);
            return $factory->output($repository);
        }

        return $repository;
    }

    /**
     * @param Currency      $instance
     * @param String|Array  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
        $data = [
            'id' => $instance ? $instance->id : '',
            'basic' => []
        ];

        if (empty($instance))
            return $data;

        $this->setEntity($instance);

        if (is_string($code)) {
            $data['basic'] = [
                  'host_type'     => $instance->host_type,
                  'host_id'       => $instance->host_id,
                  'serial'        => $instance->serial,
                  'abbreviation'  => $instance->abbreviation,
                  'mark'          => $instance->mark,
                  'exchange_rate' => $instance->exchange_rate,
                  'is_base'       => $instance->is_base,
                  'name'          => $instance->findLang($code, 'name'),
                  'description'   => $instance->findLang($code, 'description'),
                  'remarks'       => $instance->findLang($code, 'remarks'),
                  'is_enabled'    => $instance->is_enabled,
                  'updated_at'    => $instance->updated_at
            ];

        } elseif (is_array($code)) {
            foreach ($code as $language) {
                $data['basic'][$language] = [
                      'host_type'     => $instance->host_type,
                      'host_id'       => $instance->host_id,
                      'serial'        => $instance->serial,
                      'abbreviation'  => $instance->abbreviation,
                      'mark'          => $instance->mark,
                      'exchange_rate' => $instance->exchange_rate,
                      'is_base'       => $instance->is_base,
                      'name'          => $instance->findLang($language, 'name'),
                      'description'   => $instance->findLang($language, 'description'),
                      'remarks'       => $instance->findLang($language, 'remarks'),
                      'is_enabled'    => $instance->is_enabled,
                      'updated_at'    => $instance->updated_at
                ];
            }
        }

        return $data;
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @param String  $code
     * @return Array
     */
    public function getEnabledSetting(?string $host_type, ?int $host_id, string $code): array
    {
        if (
            empty($host_type)
            || empty($host_id)
        ) {
            $instance = $this->instance;
        } else {
            $instance = $this->baseQueryForRepository($host_type, $host_id);
        }
        $instance = $instance->ofEnabled();
        $records = $instance->with(['langs' => function ($query) use ($code) {
                                $query->ofCurrent()
                                      ->ofCode($code);
                            }])
                            ->orderBy('updated_at', 'DESC')
                            ->get();
        $list = [];
        foreach ($records as $record) {
            $list[$record->id] = ['id'            => $record->id,
                                  'abbreviation'  => $record->abbreviation,
                                  'mark'          => $record->mark,
                                  'exchange_rate' => $record->exchange_rate,
                                  'is_base'       => $record->is_base,
                                  'name'          => $record->findLangByKey('name')];
        }

        return $list;
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @return Array
     */
    public function getEnabledSettingId($host_type = null, $host_id = null): array
    {
        if (
            empty($host_type)
            || empty($host_id)
        ) {
            $instance = $this->instance;
        } else {
            $instance = $this->baseQueryForRepository($host_type, $host_id);
        }

        return $instance->ofEnabled()
                        ->orderBy('updated_at', 'DESC')
                        ->pluck('id')
                        ->toArray();
    }

    /**
     * @param String  $host_type
     * @param Int     $host_id
     * @return Currency
     */
    public function getBaseSetting($host_type = null, $host_id = null)
    {
        if (
            empty($host_type)
            || empty($host_id)
        ) {
            $instance = $this->instance;
        } else {
            $instance = $this->baseQueryForRepository($host_type, $host_id);
        }

        return $instance->ofBase()
                      ->first();
    }
}
