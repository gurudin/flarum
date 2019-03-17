<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_user_id')->nullable(false)->comment('用户外键');
            $table->integer('fk_posts_id')->nullable(false)->default(0)->comment('类别外键');
            $table->ipAddress('ip');
            $table->text('content')->nullable(false)->comment('内容');
            $table->text('reply')->nullable(true)->comment('留言回复');
            $table->timestamps();

            $table->index(['fk_posts_id', 'fk_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
