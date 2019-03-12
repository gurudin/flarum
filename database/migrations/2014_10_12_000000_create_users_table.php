<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('avatar');
            $table->rememberToken();
            /**
             * Api请求token
             */
            $table->string('api_token', 100)->nullable();
            /**
             * 会员开始时间与到期时间
             */
            $table->timestamp('vip_start_at')->nullable();
            $table->timestamp('vip_end_at')->nullable();
            /**
             * 金币数量
             */
            $table->integer('coins')->nullable(false)->default(0);
            /**
             * 通过其他用户分享码注册过来的用户
             */
            $table->string('share_code', '50')->nullable();
            /**
             * 用户状态
             * 0:黑名单
             * 1:正常用户
             * 10:后台管理员
             */
            $table->tinyInteger('status')->nullable(false)->default(1);
            $table->timestamps();

            // $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
