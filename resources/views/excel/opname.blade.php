@php
    $publicpath = env('APP_PUBLIC_IMG_ASET','/home/u1434218/public_html/aset');

@endphp
<table>
    <tr>
        <td>Export Data Opname Aset</td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td>
        <td>{!! Date('d-M-Y') !!}</td>
        <!-- <td><img src="C:\xampp\htdocs\ssaset\public\img\aset\opname_22.jpg"   style="width:9px;height:5px;"></td> -->
    </tr>
</table>
<table>
        <tr style="background-color: #7FC9FF;">

            <td style="background-color: #7FC9FF;" >Tanggal</td>
            <td style="background-color: #7FC9FF;" >Kode</td>
            <td style="background-color: #7FC9FF;" >Barang</td>
            <td style="background-color: #7FC9FF;" >Sub Barang</td>
            <td style="background-color: #7FC9FF;" >Tipe / Merk</td>
            <td style="background-color: #7FC9FF;" >Nomor Seri</td>
            <td style="background-color: #7FC9FF;" >Kondisi</td>
            <td style="background-color: #7FC9FF;" >Ruang Seharusnya</td>
            <td style="background-color: #7FC9FF;" >Ruang Opname</td>
            <td style="background-color: #7FC9FF;" >Maps Opname</td>

        </tr>


        @foreach($opname as $detail)
            @php

                $aset = $detail->aset;
                if (empty($aset)) {
                    $kode = "";
                    $barang = "";
                    $sub_barang = "";
                    $tipe = "";
                    $seri = "";
                } else {
                    $kode = $aset->kode;
                    $tipe = $aset->tipe;
                    $seri = $aset->seri;
                    
                    $sub = $aset->barang_sub;
                    if (empty($sub)) {
                        $sub_barang = "";
                        $barang="";
                    } else {
                        $sub_barang = $sub->deskripsi;
                        
                        $b = $sub->barang;
                        if (empty($b)) {
                            $barang = "";

                        } else {
                            $barang = $b->deskripsi;
                        }
                    }
                }

                if ($detail->aset_id == 0) {
                    $kode = "-- TANPA LABEL --";

                }
                $ruang1 = "";
                $ruang2 = "";
                if (isset( $ruang[$detail->ruang_id])) {
                    $ruang1 = $ruang[$detail->ruang_id];
                }
                if (isset($ruang[$detail->ruang_id2])) {
                    $ruang2 = $ruang[$detail->ruang_id2];
                }

                $salahruang = "";
                if ($detail->ruang_id != $detail->ruang_id2) {
                    $salahruang = ' style="background-color: #FF6161;"';
                }


                $kondisi_id = $detail->kondisi_id;
                if (is_null($kondisi_id)) {
                    $kondisi_barang = "-";
                }

                if (isset($kondisi[$kondisi_id])) {

                    $kondisi_barang = $kondisi[$kondisi_id];

                } else {
                    $kondisi_barang = "-";
                }

                 if (file_exists($publicpath.$detail->uniq_id.'.jpg')) {
                     $imgsrc = '<img src="'.$publicpath.$detail->uniq_id.'.jpg"  width="60" height="100">';
                 } else {
                     $imgsrc = '<img src="'.$publicpath.'noimage.png"  width="60" height="100">';
                 }

                // $imgsrc = "";

                $urlmap = "https://maps.google.com/maps?q=loc:".$detail->lintang.",".$detail->bujur;

            @endphp
        <tr>
            <td align='left'>{{date_format($detail->created_at,'d-m-Y')}}</td>
            <td align='left'>{{$kode}}</td>
            <td align='left'>{{$barang}}</td>
            <td align='left'>{{$sub_barang}}</td>
            <td align='left'>{{$tipe}}</td>
            <td align='center'>{{$seri}}</td>
            <td align='left'>{{$kondisi_barang}}</td>
            <td align='left'>{{$ruang1}}</td>
            <td align='left' {!! $salahruang !!} >{{$ruang2}}</td>
            <td align='left'>{{$urlmap}}</td>
            <td>{!! $imgsrc !!}</td>


        </tr>
    @endforeach
</table>