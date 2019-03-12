<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_code', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_user_id')->nullable()->comment('用户外键');
            $table->timestamp('activation_at')->nullable()->comment('使用时间');
            $table->integer('vip_valid')->nullable(false)->default(0)->comment('会员有效时间 单位:月');
            $table->string('code', 50);
            $table->timestamp('code_expiration_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invite_code');
    }
}
