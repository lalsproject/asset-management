<?php

namespace App\Exports;

use DB;
use App\Model\M_Aset;
use App\Model\M_Lokasi;
use App\Model\T_AsetPengajuan;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenyusutanExport implements FromView
{
	protected $request;

	 function __construct($request) {
            set_time_limit(600);
	        $this->request = $request;
	 }

    /**
    * @return \Illuminate\Support\Collection
    */

  	use Exportable;
    
    public function view(): View
    {
     
        $penyusutan = $this->request['penyusutan'];
        $periode = $this->request['periode'];
        return view('excel.penyusutan', [
            'penyusutan' => $penyusutan, 
            'periode' => $periode, 
        ]);
    }

}
