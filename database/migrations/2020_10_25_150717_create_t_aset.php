<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_aset', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('aset_id')->unsigned();
            $table->string('kode', 50)->default("");
            $table->integer('barang_sub_id')->unsigned();
            $table->integer('ruang_id')->unsigned();
            $table->integer('jenis_pengadaan_id')->unsigned();
            $table->integer('divisi_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('jenis_id')->unsigned();
            $table->integer('kondisi_id')->unsigned();
            $table->integer('no_urut')->unsigned();
            $table->string('tipe', 100)->nullable();
            $table->string('seri', 100)->nullable();
            $table->date('pengadaan')->nullable();
            $table->date('tgl_input')->nullable();
            $table->date('last_opname')->nullable();
            $table->double('lintang')->default("0");
            $table->double('bujur')->default("0");
            $table->integer('jumlah_susut')->unsigned()->comment('Jumlah susut dalam satuan bulan');
            $table->double('harga')->default("0");
            $table->string('keterangan', 200)->nullable();
            $table->string('supplier', 200)->nullable();
            $table->string('pengguna', 200)->nullable();
            $table->string('updated', 50)->nullable();
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
        Schema::drop('t_aset');
    }
}
