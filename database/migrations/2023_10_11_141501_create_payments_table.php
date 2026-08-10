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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('rekening_id')->nullable();
            $table->integer('assessor_one_id')->nullable();
            $table->integer('assessor_two_id')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->decimal('amount_one', 10, 2)->default(0);
            $table->decimal('amount_two', 10, 2)->default(0);
            $table->decimal('amount', 10, 2);
            $table->enum('status_accessor_one', ['1', '2', '3'])->comment('1 = Belum ditentukan, 2 = Penilaian belum bisa dilakukan, 3 = Memenuhi')->nullable();
            $table->enum('status_accessor_two', ['1', '2', '3'])->comment('1 = Belum ditentukan, 2 = Penilaian belum bisa dilakukan, 3 = Memenuhi')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
