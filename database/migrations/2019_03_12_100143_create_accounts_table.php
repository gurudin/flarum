<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_user_id')->nullable(false)->comment('用户外键');
            /**
             * 账务类别
             * income 收入
             * expend 支出
             */
            $table->string('sort', 20)->nullable(false)->comment('账务类别');
            /**
             * 触发类型
             * register 注册
             * login 登陆
             * invite 邀请
             * posts 看帖
             * buy 购买
             */
            $table->string('type', 20)->nullable(false)->comment('触发类型');
            $table->integer('fk_posts_id')->nullable()->comment('帖子外键');
            $table->integer('coin')->nullable(false)->comment('花费金币 收入正数 支持负数');
            $table->integer('balance')->nullable(false)->comment('余额');
            $table->timestamps();

            $table->index(['sort']);
            $table->index(['fk_user_id']);
            $table->index(['fk_user_id', 'fk_posts_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
