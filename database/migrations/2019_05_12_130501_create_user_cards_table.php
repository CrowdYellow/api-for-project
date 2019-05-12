<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('password')->comment('提款密码');
            $table->string('bank')->comment('开户银行');
            $table->string('region')->comment('开户省会');
            $table->string('city')->comment('开户城市');
            $table->string('bank_name')->comment('支行名称');
            $table->string('real_name')->comment('开户人姓名');
            $table->string('card_number')->comment('银行卡号');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_cards');
    }
}
