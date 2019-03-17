<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            /**
             * 阅读级别
             * 0:全用户
             * 1:登陆可以阅读
             * 2:会员可阅读（不消耗金币）
             * 3:会员可阅读 (消耗金币)
             */
            $table->integer('read_level')->nullable(false)->default(0)->comment('阅读级别');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn(['read_level', 'avatar', 'location']);
        });
    }
}
