@php
    $publicpath = env('APP_PUBLIC_IMG_ASET','/home/u1434218/public_html/aset');

@endphp
<table>
    <tr>
        <td>Export Data Penyusutan Aset</td>
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
            <td style="background-color: #7FC9FF;" >Nama Aset</td>
            <td style="background-color: #7FC9FF;" >Seri</td>
            <td style="background-color: #7FC9FF;" >Lokasi</td>
            <td style="background-color: #7FC9FF;" >Ruang</td>
            <td style="background-color: #7FC9FF;" >Harga</td>
            <td style="background-color: #7FC9FF;" >Jumlah Susut</td>
            <td style="background-color: #7FC9FF;" >Nilai Penyusutan</td>
            <td style="background-color: #7FC9FF;" >Periode</td>
        </tr>


        @foreach($penyusutan as $detail)
        <tr>
            <td align='left'>{{$detail->kode}}</td>
            <td align='left'>{{$detail->namaaset}}</td>
            <td align='left'>{{$detail->seri}}</td>
            <td align='left'>{{$detail->lokasi}}</td>
            <td align='left'>{{$detail->ruang}}</td>
            <td align='right'>{{$detail->harga}}</td>
            <td align='right'>{{$detail->jumlah_susut}}</td>
            <td align='right'>{{$detail->n}}</td>
            <td align='left'>{{$periode}}</td>
        </tr>
    @endforeach
</table>