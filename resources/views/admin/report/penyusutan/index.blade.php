@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Report Penyusutan Aset')

@section('content_header')
    Report Penyusutan Aset
@stop

@section('content')


<div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>

        <div class="card-body">

            {!! Form::open(['method' => 'POST', 'id' => 'form_filter', 'route' => ['admin.report.penyusutan.exportexcel']]) !!}

            <div class='row'>
                <div class='col-md-6'>
                    <div class="form-group">
                        <label>Kriteria</label>
                        <input name='filter_kriteria' id='filter_kriteria' value='' type=text class='form-control'>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Tahun</label>
                        {!! Form::select('filter_periode[]', $tahun, [Date('Y')], ['id' => 'filter_periode', 'class' => 'form-control select2bs4', 'style' => 'width: 100%', 'multiple' => '']) !!}
                </div>


            </div>

        </div>
        <div class="card-footer">
            <div class='row'>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" onclick="LoadData(1)">Load Data</button>
                    {!! Form::submit("Export Excel", ['class' => 'btn btn-success']) !!}

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
    @include('admin.report.penyusutan.modal')

@stop

@section('js') 
    
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
                url  : '{{ route('admin.report.penyusutan.loaddatatable') }}',
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

    
        function Detail(aset_id, periode) {

            Swal.showLoading();
 
            var datanya = "_token="+_token + "&aset_id="+aset_id + "&periode="+periode;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.report.penyusutan.detail') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                    Swal.close();
                    if (success) {
                        $('#table_area_detail').html(data.table);
                        $('#modal-title').html(data.aset.namaaset);
                        $('#hideparam').html("<input type='hidden' name='aset_id' value='"+aset_id+"'><input type='hidden' name='periode' value='"+periode+"'>");
                        $('#TambahData').modal('show');

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
    
        function DetailSemua() {

            Swal.showLoading();
 
            var datanya = $("#form_data").serialize();
            datanya += "&semua=1";

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.report.penyusutan.detail') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                    Swal.close();
                    if (success) {
                        $('#table_area_detail').html(data.table);
                        $('#hideparam').html("<input type='hidden' name='aset_id' value='"+aset_id+"'><input type='hidden' name='periode' value='"+periode+"'>");
                        $('#TambahData').modal('show');

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
    </script>
@endsection