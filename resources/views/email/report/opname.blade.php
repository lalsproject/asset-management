<!-- /* -@--extends('email.template')

-@--section('content') */ -->

	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;">Hai <b>...</b></p>
	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;">Berikut data report opname dengan filter :</p>


	<!-- Awal Table content -->
	<table border =1 width='100%' style='border-collapse:collapse' >

        @if(!empty($r['filter_tanggal']))

        <tr>
            <td>Tanggal</td>
            <td>{!! $r['filter_tanggal'] !!}/td>
        </tr>

        @endif
        @if(!empty($r['filter_kondisi']))

        <tr>
            <td>Kondisi</td>
            <td>
                @foreach ($r['filter_kondisi'] as $v  )
                    [{!! $kondisi[$v] !!}]
                @endforeach            

            </td>
        </tr>

        @endif
        @if(!empty($r['filter_ruang_status']))

        <tr>
            <td>Status</td>
            <td>
                @foreach ($r['filter_ruang_status'] as $v  )
                [{!! $ruang_status[$v] !!}]
                @endforeach            

            </td>
        </tr>

        @endif


    </table>
	<!-- Akhir Table content -->




	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;">Selamat bekerja.</p>


<!-- -@-stop -->