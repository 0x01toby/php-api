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
            $table->bigIncrements('id');
            $table->string('username', 64)->comment('用户名');
            $table->string('nickname', 64)->default('')->comment('昵称');
            $table->string("email", 128)->comment('邮箱');
            $table->string('password', 512)->default('')->comment('密码');
            $table->string('salt', 256)->default('')->comment('盐');
            $table->string('custom_token', 512)->default('')->comment('remember token name');
            $table->dateTime("created_at")->nullable()->comment("创建时间");
            $table->dateTime("updated_at")->nullable()->comment("修改时间");
            $table->unique('username');
            $table->unique('email');
            $table->unique('custom_token');
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
