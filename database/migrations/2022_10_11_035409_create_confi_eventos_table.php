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
        Schema::create('confi_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('idEvento');
            $table->string('id_coordinador');
            $table->string('secondId');
            $table->string('ultimoQR');
            $table->string('qrActual');
            $table->integer('isPrivate');
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
        Schema::dropIfExists('confi_eventos');
    }
};
