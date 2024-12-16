<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Kondisi;
class CreateMKondisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_kondisi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('uraian', 100)->default("")->unique();
            $table->string('warna', 10)->nullable();
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        M_Kondisi::Create([
                    'deskripsi' => 'Baik',
                    'uraian' => 'Dipakai',
                    'warna' => 'HIJAU',
                    'updated' => 'SEED',
                ]);

        M_Kondisi::Create([
                    'deskripsi' => 'Rusak Ringan',
                    'uraian' => 'Masih Dipakai',
                    'warna' => 'ORANGE',
                    'updated' => 'SEED',
                ]);

        M_Kondisi::Create([
                    'deskripsi' => 'Rusak Berat',
                    'uraian' => 'Daftar Penghapusan',
                    'warna' => 'MERAH',
                    'updated' => 'SEED',
                ]);

        M_Kondisi::Create([
                    'deskripsi' => 'Mutasi',
                    'uraian' => 'Dipindahkan',
                    'warna' => 'BIRU',
                    'updated' => 'SEED',
                ]);

        M_Kondisi::Create([
                    'deskripsi' => 'Hapus',
                    'uraian' => 'Persiapan untuk diahpaus dari master',
                    'warna' => 'ABU_ABU',
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
        Schema::drop('m_kondisi');
    }
}
