<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMKendaraan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_kendaraan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('aset_id')->unsigned()->unique();
            $table->string('merk_type', 100)->default("");
            $table->string('no_polisi', 15)->default("");
            $table->string('no_bpkb', 30)->default("");
            $table->string('no_mesin', 50)->default("");
            $table->string('no_rangka', 50)->default("");
            $table->integer('tahun_pembuatan')->unsigned();
            $table->date('tanggal_pembelian')->nullable();
            $table->date('berlaku_stnk')->nullable();
            $table->date('remind_stnk')->nullable();
            $table->string('asal', 50)->nullable();
            $table->string('keterangan', 100)->nullable();
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
        Schema::drop('m_kendaraan');
    }
}
