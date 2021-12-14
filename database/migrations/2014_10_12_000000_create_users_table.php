<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->enum('role', ['bks-admin', 'client-admin', 'operator-admin', 'user'])->default('user');
            $table->boolean('active')->default(false);

            $table->string('image')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            //$table->foreign('client_id')
            //        ->references('id')
            //        ->on('clients')
            //        ->onDelete('cascade');
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
