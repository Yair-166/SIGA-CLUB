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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('id_club');
            $table->string('tipo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('tipoAsistencia')->nullable();
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->string('reglas')->nullable();
            $table->text('tags')->nullable();
            $table->text('redaccionCoordinador')->nullable();
            $table->text('redaccionParticipante')->nullable();
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
        Schema::dropIfExists('eventos');
    }
};
