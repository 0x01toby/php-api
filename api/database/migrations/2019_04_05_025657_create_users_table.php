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
            $table->string("email", 32)->default('')->comment('邮箱');
            $table->string('username', 32)->default('')->comment('用户名');
            $table->string('password', 32)->default('')->comment('密码');
            $table->dateTime("created_at")->nullable()->comment("创建时间");
            $table->dateTime("updated_at")->nullable()->comment("修改时间");
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
