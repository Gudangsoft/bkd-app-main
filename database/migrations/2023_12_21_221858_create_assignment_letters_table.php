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
        Schema::create('assignment_letters', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->default(0);
            $table->integer('dosen_id');
            $table->integer('assessor_id');
            $table->string('file')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('assignment_letters');
    }
};
