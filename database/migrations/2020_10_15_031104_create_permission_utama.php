<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;

class CreatePermissionUtama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Permission::create([
                        'name' => 'user_security'
                        ]);
         Permission::create([
                        'name' => 'general_setting'
                        ]);
         Permission::create([
                        'name' => 'master_status'
                        ]);
         Permission::create([
                        'name' => 'master_jenis'
                        ]);
         Permission::create([
                        'name' => 'master_jenis_maintenance'
                        ]);
         Permission::create([
                        'name' => 'master_kondisi'
                        ]);
         Permission::create([
                        'name' => 'master_divisi'
                        ]);
         Permission::create([
                        'name' => 'master_satuan'
                        ]);
         Permission::create([
                        'name' => 'master_penyusutan'
                        ]);
         Permission::create([
                        'name' => 'master_jenis_pengadaan'
                        ]);
         Permission::create([
                        'name' => 'master_barang'
                        ]);
         Permission::create([
                        'name' => 'master_barang_sub'
                        ]);
         Permission::create([
                        'name' => 'master_lokasi'
                        ]);
         Permission::create([
                        'name' => 'master_ruang'
                        ]);


         Permission::create([
                        'name' => 'master_aset'
                        ]);
         Permission::create([
                        'name' => 'master_tanah'
                        ]);
         Permission::create([
                        'name' => 'master_kendaraan'
                        ]);
        //  Permission::create([
        //                 'name' => 'master_device'
        //                 ]);

         Permission::create([
                        'name' => 'hapus_aset'
                        ]);




         Permission::create([
                        'name' => 'transaksi_aset'
                        ]);

         Permission::create([
                        'name' => 'transaksi_maintenance'
                        ]);
         Permission::create([
                        'name' => 'transaksi_opname'
                        ]);

        Permission::create([
                        'name' => 'report_opname'
                        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


    }
}
