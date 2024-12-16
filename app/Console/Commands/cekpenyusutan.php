<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Fungsi\Project;

use Illuminate\Support\Facades\Mail;
use ZipArchive;
use DB;

class cekpenyusutan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cekpenyusutan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Script Function';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $sql = "select *, harga - total as selisih from 
            (
                select m_aset.id, m_aset.harga, coalesce(sum(susut.nilai),0) as total
                    from m_aset left join t_penyusutan as susut on m_aset.id = susut.aset_id
                    group by m_aset.id
            ) as x where  harga - total < -0.5 or harga - total > 0.5 ";

        $data = DB::select(DB::raw($sql));

        foreach ($data as $key => $value) {
            $this->info("Proses Aset ".$value->id);
            $x = Project::susunpenyusutan($value->id);
        }

        $this->info("Selesai ");


    }
}
