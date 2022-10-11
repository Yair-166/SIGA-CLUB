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
        Schema::create('_asistencias', function (Blueprint $table) {
            $table->id();
            $table->string('idEvento');
            $table->string('idUsuario');
            $table->string('rolUsuario');
            $table->integer('tipoAsistencia');
            $table->time('horaEntrada');
            $table->time('horaSalida');
            $table->float('asistenciaTotal');
            $table->text('constanciaGenerada');
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
        Schema::dropIfExists('_asistencias');
    }
};
