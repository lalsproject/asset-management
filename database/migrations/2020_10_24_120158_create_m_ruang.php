<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\M_Ruang;

class CreateMRuang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_ruang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('lokasi_id')->unsigned();
            $table->string('kode', 50)->default("")->unique();
            $table->string('deskripsi', 100)->default("");
            $table->string('updated', 50)->nullable();
            $table->timestamps();

            // $table->foreign('lokasi_id')
            //     ->references('id')
            //     ->on('m_lokasi')
            //     ->onDelete('restrict');

        });

        // M_Ruang::Create([
        //     'lokasi_id' => 1,
        //     'kode' => 'HO-KTR',
        //     'deskripsi' => 'Kantor',
        //     'updated' => 'SEED',
        // ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_ruang');
    }
}
