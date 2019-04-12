<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_monitor_id');
            $table->integer('rank')->comment('位置');
            $table->string('address', 150)->comment('地址');
            $table->string('belong')->coment('地址所属');
            $table->float('quantity', 14, 2)->comment('持有量');
            $table->float('percentage', 6, 4)->comment('百分比');
            $table->timestamps();

            $table->index(['fk_monitor_id']);
            $table->index(['address']);
            
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
        Schema::dropIfExists('monitor_detail');
    }
}
