<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;

class CreateTPenyusutan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_penyusutan', function (Blueprint $table) {
            $table->id();
            $table->biginteger('aset_id')->nullable()->default("0");
            $table->date('periode')->nullable()->default("2000-01-01")->comment("periode penyusutan");
            $table->double('nilai')->nullable()->default("0")->comment("Nilai penyusutan per bulan");
            $table->timestamps();
        });
        Permission::create([
            'name' => 'report_penyusutan'
            ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_penyusutan');
        $perm = Permission::findByName("report_penyusutan");
        $perm->delete();

    }
}
