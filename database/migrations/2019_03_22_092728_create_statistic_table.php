<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable(false)->comment('访问地址');
            $table->string('referrer')->nullable(true)->comment('来源地址');
            $table->string('useragent')->nullabel(true);
            $table->string('platform', 100);
            $table->string('language', 20);
            $table->integer('width')->nullable(true)->comment('屏幕宽度');
            $table->integer('height')->nullable(true)->comment('屏幕高度');
            $table->ipAddress('ip');
            $table->timestamp('created_at');

            $table->index(['ip']);
            $table->index(['language']);
            $table->index(['platform']);
            $table->index(['url']);
            
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
        Schema::dropIfExists('statistic');
    }
}
