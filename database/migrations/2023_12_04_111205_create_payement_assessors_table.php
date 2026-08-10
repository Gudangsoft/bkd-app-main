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
        Schema::create('payement_assessors', function (Blueprint $table) {
            $table->id();
            $table->integer('rekening_id');
            $table->integer('assessor_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('proof_of_payment')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->integer('status');
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
        Schema::dropIfExists('payement_assessors');
    }
};
