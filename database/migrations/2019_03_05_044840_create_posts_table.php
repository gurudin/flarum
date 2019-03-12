<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_category_id')->nullable(false)->default(0)->comment('类别外键');
            $table->integer('fk_user_id')->nullable(false)->comment('用户外键');
            /**
             * 阅读级别
             * 0:全用户
             * 1:登陆可以阅读
             * 2:会员可阅读（不消耗金币）
             * 3:会员可阅读 (消耗金币)
             */
            $table->integer('read_level')->nullable(false)->default(0)->comment('阅读级别');
            $table->integer('coin')->nullable(false)->default(0)->comment('消耗积分');
            $table->string('title', 255)->nullable(false)->comment('标题');
            $table->integer('weight')->nullable(false)->default(0)->comment('权重 从大到小');
            $table->string('cover')->nullable(false)->default('')->comment('封面图片');
            $table->string('remark', 255)->nullable(false)->default('')->comment('简介');
            $table->text('content')->comment('内容');
            $table->string('tags')->nullable(false)->default('')->comment('标签');
            $table->integer('pv')->defaultValue(0)->comment('访问量');
            $table->tinyInteger('status')->nullable(false)->default(2)->comment('0:下线 1:上线 2:待审核');
            $table->timestamps();

            $table->index(['fk_category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
