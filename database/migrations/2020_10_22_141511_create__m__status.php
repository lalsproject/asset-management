<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\M_Status;

class CreateMStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 50)->default("")->unique();
            $table->string('deskripsi_init', 50)->default("")->unique();
            $table->string('warna', 10)->nullable();
            $table->string('updated', 50)->nullable();
            $table->timestamps();

        });


        M_Status::Create([
                    'deskripsi' => 'Aktif',
                    'deskripsi_init' => 'Aktif',
                    'warna' => 'HIJAU',
                    'updated' => 'SEED',
                ]);

        M_Status::Create([
                    'deskripsi' => 'Musnah',
                    'deskripsi_init' => 'Musnah',
                    'warna' => 'MERAH',
                    'updated' => 'SEED',
                ]);

        M_Status::Create([
                    'deskripsi' => 'Mutasi',
                    'deskripsi_init' => 'Mutasi',
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
        Schema::drop('m_status');
    }
}
