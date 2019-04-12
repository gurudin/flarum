<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token_name', 100)->comment('token名称');
            $table->float('market_cap', 10, 2)->comment('市值');
            $table->float('price_usd', 10, 4)->comment('价格');
            $table->float('price_eth', 10, 8)->comment('eth价格');
            $table->float('applies', 10, 2)->comment('涨跌幅度 百分比');
            $table->integer('addresses')->comment('地址数量');
            $table->integer('transfers')->comment('转账数量');
            $table->float('volume', 10, 2)->comment('24h 交易价值');
            $table->float('market_capitalization', 10, 2)->comment('已发行市场总价值');
            $table->float('supply', 14, 2)->comment('总量');
            $table->timestamps();

            $table->index(['token_name']);
            $table->index(['token_name', 'created_at']);

            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor');
    }
}
