<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Barang;

class CreateMBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_barang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('kode', 50)->default("")->unique();
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('updated', 50)->nullable();
            $table->timestamps();

            // $table->foreign('satuan_id')
            //     ->references('id')
            //     ->on('m_satuan')
            //     ->onDelete('restrict');

        });
        
        // M_Barang::Create([
        //     'satuan_id' => 1,
        //     'kode' => 'ELK',
        //     'deskripsi' => 'ELEKTRONIK',
        //     'updated' => 'SEED',
        // ]);
        
        // M_Barang::Create([
        //     'satuan_id' => 1,
        //     'kode' => 'PKR',
        //     'deskripsi' => 'PERLENGKAPAN KANTOR',
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
        Schema::drop('m_barang');
    }
}
