<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recomment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recomment_id')->nullable()->comment('类别id or 帖子id');
            $table->string('url')->nullable()->comment('外链url');
            /**
             * 推荐类型
             * posts 帖子
             * category 类型
             * url 外链推荐
             */
            $table->string('type', 50)->nullable(false)->comment('推荐类型');
            /**
             * 位置
             * 1 = 首页左部幻灯片
             * 2 = 首页中部
             */
            $table->integer('position')->nullable(false)->comment('位置');
            $table->string('cover')->nullable()->comment('封面图片');
            $table->string('remark')->nullable()->comment('描述');
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('0:下线 1:上线');
            $table->timestamps();

            $table->index(['type']);
            $table->index(['type', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recomment');
    }
}
