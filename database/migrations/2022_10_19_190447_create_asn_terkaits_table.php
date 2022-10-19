<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsnTerkaitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asn_terkait', function (Blueprint $table) {
            $table->id();
            $table->string('id_users', 191)->nullable();
            $table->string('nip_terkait', 20)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('gelar_depan', 20)->nullable();
            $table->string('gelar_belakang', 20)->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->string('foto', 191)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('id_jenis_kelamin', 10)->nullable();
            $table->string('id_agama', 10)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nama_jabatan', 255)->nullable();
            $table->integer('id_sotk')->nullable();
            $table->integer('id_skpd')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('asn_terkait');
    }
}
