<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRRLoggersTable extends Migration
{
    public function up()
    {
        $table_name = config('rrlogger.table_name');
        if (empty($table_name)) {
            $table_name = 'rrloggers';
        }
        Schema::create($table_name, function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('endpoint');
            $table->string('uri');
            $table->string('method');
            $table->ipAddress()->nullable();
            $table->text('content')->nullable();
            $table->text('request')->nullable();
            $table->text('request_type')->nullable(); // incoming or outgoing
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
        $table_name = config('rrlogger.table_name');
        if (empty($table_name)) {
            $table_name = 'rrloggers';
        }
        Schema::dropIfExists($table_name);
    }
}
