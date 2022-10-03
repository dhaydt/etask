<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_staff')->nullable();
            $table->integer('nip')->nullable();
            $table->string('nama')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->integer('id_jenis_asn')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('foto')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->integer('id_agama')->nullable();
            $table->integer('active')->nullable();
            $table->text('alamat')->nullable();
            $table->integer('no_hp')->nullable();
            $table->integer('nik')->nullable();
            $table->integer('id_jabatan')->nullable();
            $table->integer('id_jenis_jabatan')->nullable();
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
        Schema::dropIfExists('staff_details');
    }
}
