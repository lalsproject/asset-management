<table>
    <tr>
        <td>Export Data ASet</td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td>
        <td>{!! Date('d-M-Y') !!}</td>
    </tr>
</table>
<table>
        <tr style="background-color: #7FC9FF;">

            <td style="background-color: #7FC9FF;" >Kode</td>
            <td style="background-color: #7FC9FF;" >QR</td>
            <td style="background-color: #7FC9FF;" >Lokasi</td>
            <td style="background-color: #7FC9FF;" >Ruang</td>
            <td style="background-color: #7FC9FF;" >Sub Barang</td>
            <td style="background-color: #7FC9FF;" >Tipe / Merk</td>
            <td style="background-color: #7FC9FF;" >Nomor Seri</td>
            <td style="background-color: #7FC9FF;" >Kondisi</td>
            <td style="background-color: #7FC9FF;" >Pengguna</td>
            <td style="background-color: #7FC9FF;" >Keterangan</td>
            <td style="background-color: #7FC9FF;" >Tanggal Input</td>
            <td style="background-color: #7FC9FF;" >Tanggal Input Data</td>
            <td style="background-color: #7FC9FF;" >Harga</td>
            <td style="background-color: #7FC9FF;" >Penyusutan</td>

        </tr>



        @foreach($data as $detail)
        <tr>
            <td align='left'>{{$detail->kode}}</td>
            <td align='left'>{{$detail->qr }}</td>
            <td align='left'>{{$detail->ruang->lokasi->deskripsi}}</td>
            <td align='left'>{{$detail->ruang->deskripsi}}</td>
            <td align='left'>{{$detail->barang_sub->deskripsi}}</td>
            <td align='center'>{{$detail->tipe}}</td>
            <td align='left'>{{$detail->seri}}</td>
            <td align='right'>{{$detail->kondisi->deskripsi}}</td>
            <td align='right'>{{$detail->pengguna}}</td>
            <td align='right'>{{$detail->keterangan}}</td>
            <td align='left'>{{$detail->tgl_input}}</td>
            <td align='left'>{{$detail->created_at}}</td>
            <td align='left'>{{$detail->harga}}</td>
            <td align='left'>{{$detail->jumlah_susut}}</td>
                
        </tr>
    @endforeach
</table>