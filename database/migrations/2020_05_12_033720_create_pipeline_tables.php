<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipelineTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->default('');
            $table->timestamps();
        });

        Schema::create('pipeline_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->default('');
            $table->string('content', 2000)->default('');
            $table->foreignId('user_id')->default(0);
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
        //
    }
}
