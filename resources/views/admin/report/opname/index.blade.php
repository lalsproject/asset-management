@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Report Opname Aset')

@section('content_header')
    Report Opname Aset
@stop

@section('content')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@stop


<div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>

        <div class="card-body">

            {!! Form::open(['method' => 'POST', 'id' => 'form_filter', 'route' => ['admin.report.opname.exportexcel']]) !!}

            <div class='row'>
                <div class='col-md-6'>
                    <div class="form-group">
                        <label>Kode Aset</label>
                        <input name='filter_kode' id='filter_kode' value='' type=text class='form-control'>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class="form-group">
                        <label>Periode Tanggal</label>
                        <input name='filter_tanggal' id='filter_tanggal' value='' type=text class='form-control'>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Kondisi</label>
                        {!! Form::select('filter_kondisi[]', $kondisi, [], ['id' => 'filter_kondisi', 'class' => 'form-control select2bs4', 'style' => 'width: 100%', 'multiple' => '']) !!}
                </div>

                <div class="col-md-6 form-group">
                    <label>Status Ruang</label>
                        {!! Form::select('filter_ruang_status[]', $ruang_status, [], ['id' => 'filter_ruang_status', 'class' => 'form-control select2bs4', 'style' => 'width: 100%', 'multiple' => '']) !!}
                </div>

            </div>

        </div>
        <div class="card-footer">
            <div class='row'>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" onclick="LoadData(1)">Load Data</button>
                    {!! Form::submit("Export Excel", ['name' => 'tipe', 'class' => 'btn btn-success']) !!}
                    <button type="button" class="btn btn-warning" onclick="KirimEmail()">Kirim Email</button>

                    <span class="float-right">
                        {!! Form::submit("Export Excel Summary", ['name' => 'tipe', 'class' => 'btn btn-primary']) !!}
                    </span>

                    {!! Form::close() !!}
                <!-- </div>
                <div class="col-md-9"> -->
                    <!-- <span class="float-right">
                        <button type="button" class="btn btn-success" onclick="BuatBaru()">Buat Baru</button>
                    </span> -->
                </div>        
            </div>

        </div>

    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_list')</h3>

        </div>

        <div class="card-body">

            <div class='row'>
                <div class="col-md-12">
                    <span class="float-right">
                        <div id='pagination'>
                            {!! $table['pagination'] !!}
                        </div>
                    </span>
                </div>        
            </div>
            <br>
            <div id='tablearea' class="table-responsive">
                {!! $table['table'] !!}
            </div>
        </div>

        <div class="card-footer">
                <div class="col-md-12">
                    <span class="float-right">
                        <div id='pagination2'>
                            {!! $table['pagination'] !!}
                        </div>
                    </span>
                </div>        
        </div>
    </div>
    @include('admin.report.opname.modal')

@stop

@section('js') 
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script>
         $(function () {
            $('#filter_tanggal').daterangepicker({
                    startDate: '{!! Date("d-m-Y", strtotime("-1 month")) !!}',
                    "autoApply": true,
                    "autoclose": true,
                    locale: {
                        format: 'DD-MM-YYYY',
                        separator: " sampai ",
                    }
            });
         })

    </script>


    <script>
        window.halaman_aktif = 1;


        $('#form_filter').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            LoadData(1);
            return false;
          }
        });

        $('#form_data').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            e.preventDefault();
            return false;
          }
        });


       

        function LoadData(halaman) {

            Swal.showLoading();
 
            var datanya = $("#form_filter").serialize();

            datanya += "&halaman=" + halaman;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.report.opname.loaddatatable') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                  
   
                    Swal.close();
                    if (success) {
                        halaman_aktif = halaman;
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#pagination2').html(data.pagination);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'error '+ status + ' ' + pesan
                        })

                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                            icon: 'error',
                            text: 'error '+ status + ' ' + error
                        })

                }
            })   

        }

        function Gambar(id) {
            var imgurl = '{{url('/admin/report/opname/image/-id-')}}';

            var imu =  imgurl.replace('-id-', id);

            console.log(imu);
            $('#showgambar').attr('src', imu);

            $('#TambahData').modal('show');

        }

        function Maps(lintang, bujur) {
            var murl = "http://maps.google.com/maps?q=loc:"+lintang+"," + bujur;

            window.open(murl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes");
        }
        
        

        function KirimEmail() {

            var datanya = $("#form_filter").serialize() ;

            Swal.fire({
                title: "Masukkan alamat email tujuan",
                input: "email",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Kirim Email",
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: async (tujuan) => {
                    return $.ajax({
                        url: '{{ route('admin.report.opname.exportexcel') }}',
                        type: 'POST',
                        data: datanya + "&tipe=email&tujuan=" + tujuan
                    })
                    .then(response => {
                        return response
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: "Error"
                        })
                    })

                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {
                    
                    Swal.fire({
                        icon: 'success',
                        text: pesan,
                        timer: 2000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })

                }
                
            });


        }

    </script>
@endsection