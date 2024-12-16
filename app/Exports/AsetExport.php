<?php

namespace App\Exports;

use DB;
use App\Model\M_Aset;
use App\Model\M_Lokasi;
use App\Model\M_Ruang;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AsetExport implements FromView
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
     
        

        // $sql = $this->request['sql'];
        // $cabang = $this->request['cabang'];
        // $data = DB::select( DB::raw($sql));

        // dd($this->request);

        if (!empty($this->request)) {
            $kriteria = $this->request->input('kriteria');
            $lokasi = $this->request->input('filter_lokasi');

        }

        $data = M_Aset::where('id','!=', '0');

        if (!empty($lokasi)) {
            $ruang = M_Ruang::wherein('lokasi_id', $lokasi)->pluck('id')->toarray();
            $data = $data->wherein('ruang_id', $ruang);

        }



        if(!empty($kriteria)){
            $data = $data->where('tipe','LIKE','%'.$kriteria.'%')
                            ->orwhere('seri','LIKE','%'.$kriteria.'%')
                            ->orwhere('kode','LIKE','%'.$kriteria.'%')
                            ->orwhere('qr','LIKE','%'.$kriteria.'%')
                           ;
        }

        $data = $data->get();
 

        return view('excel.aset', [
            'data' => $data 
        ]);
    }

}
