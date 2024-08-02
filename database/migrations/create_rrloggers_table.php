<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRRLoggersTable extends Migration
{
    public function up()
    {
        Schema::create('rrloggers', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('endpoint');
            $table->string('uri');
            $table->string('method');
            $table->ipAddress()->nullable();
            $table->text('content')->nullable();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->integer('milliseconds');
            $table->integer('status');
            $table->boolean('success');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rrlogers');
    }
}
