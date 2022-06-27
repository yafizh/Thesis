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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('name');
            $table->string('position');
            $table->boolean('sex');
            $table->string('phone_number')->unique();
            $table->string('academic_background');
            $table->string('start_date');
            $table->string('birth');
            $table->string('address');
            $table->string('file_image');
            $table->string('file_ijazah');
            $table->string('file_sk_pengangkatan');
            $table->string('file_ktp');
            $table->string('status');
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
        Schema::dropIfExists('employees');
    }
};
