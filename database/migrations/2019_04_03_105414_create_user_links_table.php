<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid')->comment("关联用户id");
            $table->string('type')->comment("第三方平台类型");
            $table->string('openid')->comment("第三方平台用户标识");
            $table->timestamps();
            $table->unique(['uid', 'type']);
            $table->index(['uid', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_links');
    }
}
