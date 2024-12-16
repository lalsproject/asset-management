<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Divisi;

class CreateMDivisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_divisi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        M_Divisi::Create([
                    'deskripsi' => 'Keuangan',
                    'updated' => 'SEED',
                ]);

        M_Divisi::Create([
                    'deskripsi' => 'Operasional',
                    'updated' => 'SEED',
                ]);

        M_Divisi::Create([
                    'deskripsi' => 'General Affair',
                    'updated' => 'SEED',
                ]);

        M_Divisi::Create([
                    'deskripsi' => 'IT',
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
        Schema::drop('m_divisi');
    }
}
