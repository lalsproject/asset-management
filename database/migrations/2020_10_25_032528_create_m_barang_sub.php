<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_BarangSub;


class CreateMBarangSub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_barang_sub', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('barang_id')->unsigned();
            $table->integer('satuan_id')->unsigned();
            $table->string('kode', 50)->default("")->unique();
            $table->string('deskripsi', 100)->default("");
            $table->string('updated', 50)->nullable();
            $table->timestamps();

            // $table->foreign('barang_id')
            //     ->references('id')
            //     ->on('m_barang')
            //     ->onDelete('restrict');

        });

                
        M_BarangSub::Create([
            'barang_id' => 1,
            'kode' => 'ELK-LAP',
            'deskripsi' => 'LAPTOP',
            'updated' => 'SEED',
        ]);

        M_BarangSub::Create([
            'barang_id' => 1,
            'kode' => 'ELK-HP',
            'deskripsi' => 'HANDPHONE',
            'updated' => 'SEED',
        ]);

        M_BarangSub::Create([
            'barang_id' => 1,
            'kode' => 'ELK-TV',
            'deskripsi' => 'TELEVISI',
            'updated' => 'SEED',
        ]);

        M_BarangSub::Create([
            'barang_id' => 2,
            'kode' => 'PKR-MJ',
            'deskripsi' => 'MEJA KANTOR',
            'updated' => 'SEED',
        ]);

        M_BarangSub::Create([
            'barang_id' => 2,
            'kode' => 'PKR-KR',
            'deskripsi' => 'KURSI KANTOR',
            'updated' => 'SEED',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_barang_sub');
    }
}
