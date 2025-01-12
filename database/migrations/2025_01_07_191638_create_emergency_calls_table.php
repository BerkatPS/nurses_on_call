<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('emergency_calls', function (Blueprint $table) {
            $table->id(); // Incremental ID
            $table->unsignedBigInteger('user_id');
            $table->string('location');
            $table->text('description');
            $table->string('emergency_type');
            $table->string('status')->default('pending');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedBigInteger('assigned_nurse_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('assigned_nurse_id')
                ->references('id')
                ->on('nurses')
                ->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_calls');
    }
};
