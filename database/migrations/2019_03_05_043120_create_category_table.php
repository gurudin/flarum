<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable(false)->default(0)->comment('父类ID');
            $table->string('category', 50)->nullable(false)->comment('类别名称');
            $table->integer('weight')->nullable(false)->default(0)->comment('权重 从大到小');
            $table->string('alias', 100)->comment('别名');
            $table->string('color', 20)->comment('颜色');
            $table->string('pic', 150)->default('')->comment('类别图片');
            $table->string('remark')->default('')->comment('描述');
            $table->tinyInteger('enabled')->default(1)->comment('0:不启动 1:启用');
            $table->timestamps();

            // $table->index(['parent_id']);
            // $table->index(['alias']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}
