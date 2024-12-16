<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Jenis;

class CreateMJenis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 50)->default("")->unique();
            $table->string('deskripsi_init', 50)->default("")->unique();
            $table->string('warna', 10)->nullable();
            $table->string('updated', 50)->nullable();
            $table->timestamps();

        });


        M_Jenis::Create([
                    'deskripsi' => 'Perlengkapan',
                    'deskripsi_init' => 'Perlengkapan',
                    'warna' => 'HIJAU',
                    'updated' => 'SEED',
                ]);

        M_Jenis::Create([
                    'deskripsi' => 'Tanah',
                    'deskripsi_init' => 'Tanah',
                    'warna' => 'MERAH',
                    'updated' => 'SEED',
                ]);

        M_Jenis::Create([
                    'deskripsi' => 'Bangunan',
                    'deskripsi_init' => 'Bangunan',
                    'warna' => 'MERAH',
                    'updated' => 'SEED',
                ]);
        M_Jenis::Create([
                    'deskripsi' => 'Kendaraan',
                    'deskripsi_init' => 'Kendaraan',
                    'warna' => 'BIRU',
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
        Schema::drop('m_jenis');
    }
}
