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
        Schema::create('archieves', function (Blueprint $table) {
            $table->id();
            $table->string('file', '255');
            $table->timestamp('submitted_date')->nullable();
            $table->timestamp('forwarded_date')->nullable();
            $table->timestamp('determined_date')->nullable();
            $table->unsignedTinyInteger('status')->comment('1 = submitted, 2 = forwarded, 3 = accepted, 4 = rejected');
            $table->text('comments');
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
        Schema::dropIfExists('archieves');
    }
};
