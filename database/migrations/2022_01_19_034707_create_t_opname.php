<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_opname', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('aset_id');
            $table->integer('kondisi_id')->unsigned();
            $table->integer('device_id')->unsigned();
            $table->integer('ruang_id')->unsigned();
            $table->integer('ruang_id2')->unsigned()->comment('Ruang tempat scan dilakukan');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('deskripsi', 200)->default("")->nullable()->comment('Disiapkan untuk barang tanpa label');
            $table->string('keterangan', 200)->default("")->nullable();
            $table->string('pengguna', 200)->default("")->nullable();
            $table->double('lintang')->default(0)->nullable();
            $table->double('bujur')->default(0)->nullable();
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
        Schema::dropIfExists('t_opname');
    }
}
