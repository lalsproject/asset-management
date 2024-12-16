<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBangunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_bangunan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('aset_id')->unsigned()->unique();
            $table->string('deskripsi', 100)->default("");
            $table->string('alamat', 200)->default("");
            $table->integer('luas_tanah')->unsigned();
            $table->integer('luas_bangunan')->unsigned();
            $table->string('no_sertifikat', 50)->nullable();
            $table->string('jenis_sertifikat', 50)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('updated', 50)->nullable();
            $table->timestamps();

            // $table->foreign('aset_id')
            //     ->references('id')
            //     ->on('m_aset')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_bangunan');
    }
}
