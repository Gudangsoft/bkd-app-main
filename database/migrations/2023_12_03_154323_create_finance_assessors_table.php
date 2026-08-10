<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_assessors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('dosen_id');
            $table->string('dosen_status');
            $table->decimal('amount', 15, 2);
            $table->string('assessor_of');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('finance_assessors');
    }
};
