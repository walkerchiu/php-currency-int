<?php

namespace WalkerChiu\Currency\Models\Forms;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class CurrencyFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (int) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'host_type'     => trans('php-currency::currency.host_type'),
            'host_id'       => trans('php-currency::currency.host_id'),
            'serial'        => trans('php-currency::currency.serial'),
            'abbreviation'  => trans('php-currency::currency.abbreviation'),
            'mark'          => trans('php-currency::currency.mark'),
            'exchange_rate' => trans('php-currency::currency.exchange_rate'),
            'is_base'       => trans('php-currency::currency.is_base'),
            'is_enabled'    => trans('php-currency::currency.is_enabled'),

            'name'        => trans('php-currency::currency.name'),
            'description' => trans('php-currency::currency.description'),
            'remarks'     => trans('php-currency::currency.remarks')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'host_type'     => 'required_with:host_id|string',
            'host_id'       => 'required_with:host_type|integer|min:1',
            'serial'        => '',
            'abbreviation'  => 'required|string',
            'mark'          => 'required|string',
            'exchange_rate' => 'required|numeric|min:0|not_in:0',
            'is_base'       => 'boolean',
            'is_enabled'    => 'boolean',

            'name'        => 'required|string|max:255',
            'description' => '',
            'remarks'     => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.currency.currencies').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'             => trans('php-core::validation.required'),
            'id.integer'              => trans('php-core::validation.integer'),
            'id.min'                  => trans('php-core::validation.min'),
            'id.exists'               => trans('php-core::validation.exists'),
            'host_type.required_with' => trans('php-core::validation.required_with'),
            'host_type.string'        => trans('php-core::validation.string'),
            'host_id.required_with'   => trans('php-core::validation.required_with'),
            'host_id.integer'         => trans('php-core::validation.integer'),
            'host_id.min'             => trans('php-core::validation.min'),
            'abbreviation.required'   => trans('php-core::validation.required'),
            'abbreviation.string'     => trans('php-core::validation.string'),
            'mark.required'           => trans('php-core::validation.required'),
            'mark.string'             => trans('php-core::validation.string'),
            'exchange_rate.required'  => trans('php-core::validation.required'),
            'exchange_rate.numeric'   => trans('php-core::validation.numeric'),
            'exchange_rate.min'       => trans('php-core::validation.min'),
            'exchange_rate.not_in'    => trans('php-core::validation.not_in'),
            'is_base.boolean'         => trans('php-core::validation.boolean'),
            'is_enabled.boolean'      => trans('php-core::validation.boolean'),

            'name.required' => trans('php-core::validation.required'),
            'name.string'   => trans('php-core::validation.string'),
            'name.max'      => trans('php-core::validation.max')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (
                isset($data['host_type'])
                && isset($data['host_id'])
            ) {
                if (
                    config('wk-currency.onoff.site-mall')
                    && !empty(config('wk-core.class.site-mall.site'))
                    && $data['host_type'] == config('wk-core.class.site-mall.site')
                ) {
                    $result = DB::table(config('wk-core.table.site-mall.sites'))
                                ->where('id', $data['host_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('host_id', trans('php-core::validation.exists'));
                } elseif (
                    config('wk-currency.onoff.group')
                    && !empty(config('wk-core.class.group.group'))
                    && $data['host_type'] == config('wk-core.class.group.group')
                ) {
                    $result = DB::table(config('wk-core.table.group.groups'))
                                ->where('id', $data['host_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('host_id', trans('php-core::validation.exists'));
                }
            }

            if (
                isset($data['is_base'])
                && isset($data['is_enabled'])
            ) {
                if (
                    $data['is_base']
                    && $data['is_enabled'] == 0
                ) {
                    $validator->errors()->add('is_enabled', trans('php-core::validation.not_allowed'));
                }
            }
        });
    }
}
