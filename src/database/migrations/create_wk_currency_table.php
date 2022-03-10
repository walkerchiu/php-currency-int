<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkCurrencyTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.currency.currencies'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('host');
            $table->string('serial')->nullable();
            $table->string('abbreviation');
            $table->string('mark');
            $table->unsignedDecimal('exchange_rate')->default(1);
            $table->boolean('is_base')->default(0);
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->index('is_base');
            $table->index('is_enabled');
        });
        if (!config('wk-currency.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.currency.currencies_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.currency.currencies_lang'));
        Schema::dropIfExists(config('wk-core.table.currency.currencies'));
    }
}
