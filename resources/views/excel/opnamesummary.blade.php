@php
    $publicpath = env('APP_PUBLIC_IMG_ASET','/home/u1434218/public_html/aset');

@endphp
<table>
    <tr>
        <td>Export Data Opname Aset Summary</td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td>
        <td>{!! Date('d-M-Y') !!}</td>
        <!-- <td><img src="C:\xampp\htdocs\ssaset\public\img\aset\opname_22.jpg"   style="width:9px;height:5px;"></td> -->
    </tr>
</table>
<table>
        <tr style="background-color: #7FC9FF;">

            <td style="background-color: #7FC9FF;" >Kode</td>
            <td style="background-color: #7FC9FF;" >Barang</td>
            <td style="background-color: #7FC9FF;" >Sub Barang</td>
            <td style="background-color: #7FC9FF;" >Tipe / Merk</td>
            <td style="background-color: #7FC9FF;" >Nomor Seri</td>
        </tr>


        @foreach($aset as $detail)
            @php

                $kode = $detail->kode;
                $tipe = $detail->tipe;
                $seri = $detail->seri;
                
                $sub = $detail->barang_sub;
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

                $history = $detail->historyopname();

            @endphp
        <tr>
            <td style="text-align: left; vertical-align: middle;" rowspan=3>{{$kode}}</td>
            <td style="text-align: left; vertical-align: middle;" rowspan=3>{{$barang}}</td>
            <td style="text-align: left; vertical-align: middle;" rowspan=3>{{$sub_barang}}</td>
            <td style="text-align: left; vertical-align: middle;" rowspan=3>{{$tipe}}</td>
            <td style="text-align: center; vertical-align: middle;"  rowspan=3>{{$seri}}</td>

            @foreach ( $history as $d )
                @php

                    $ruang1 = "";
                    if (isset( $ruang[$detail->ruang_id])) {
                        $ruang1 = $ruang[$detail->ruang_id];
                    }


                    $kondisi_id = $d->kondisi_id;
                    if (is_null($kondisi_id)) {
                        $kondisi_barang = "-";
                    }

                    if (isset($kondisi[$kondisi_id])) {

                        $kondisi_barang = $kondisi[$kondisi_id];

                    } else {
                        $kondisi_barang = "-";
                    }

                    if (file_exists($publicpath.$d->uniq_id.'.jpg')) {
                        $imgsrc = '<img src="'.$publicpath.$d->uniq_id.'.jpg"  width="60" height="100">';
                    } else {
                        $imgsrc = '<img src="'.$publicpath.'noimage.png"  width="60" height="100">';
                    }

                @endphp

                <td>{!! $imgsrc !!}</td>

            @endforeach
        </tr><tr>

            @foreach ( $history as $d )
                @php

                    $ruang2 = "";
                    if (isset( $ruang[$d->ruang_id2])) {
                        $ruang2 = $ruang[$d->ruang_id2];
                    }


                @endphp

                <td>{!! $ruang2 !!}</td>

            @endforeach
        </tr><tr>

            @foreach ( $history as $d )
                @php

                    $kondisi_id = $d->kondisi_id;
                    if (is_null($kondisi_id)) {
                        $kondisi_barang = "-";
                    }

                    if (isset($kondisi[$kondisi_id])) {

                        $kondisi_barang = $kondisi[$kondisi_id];

                    } else {
                        $kondisi_barang = "-";
                    }

                    $t = date_format(date_create($d->tanggal),'Y-m-d');

                @endphp

                <td>{!! $kondisi_barang !!} ( {!! $t !!} )</td>

            @endforeach

        </tr>
    @endforeach
</table>