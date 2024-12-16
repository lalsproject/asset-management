@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Divisi')

@section('content_header')
    Master Divisi
@stop

@section('content')



    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>

        <div class="card-body">

            {!! Form::open(['id' => 'form_filter']) !!}

            <div class='row'>

                <div class='col-lg-12'>
                    <div class="form-group">
                        <label>Kriteria</label>
                        <input type="text" class="form-control" id="filter_kriteria" name="filter_kriteria" placeholder="@lang('global.app_search')">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
        <div class="card-footer">
            <div class='row'>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" onclick="LoadData(1)">Load Data</button>

                    <span class="float-right">
                        <button type="button" class="btn btn-success" onclick="BuatBaru()">Buat Baru</button>
                    </span>
                </div>
            </div>

        </div>
    </div>

    <div class="card card-primary">
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


            <div id='tablearea'>
                {!! $table['table'] !!}
            </div>
        </div>


        <div class="card-footer">
            <div class="col-md-12">
                <button type="button" class="btn btn-xs btn-danger" onclick="HapusTerpilih()" id='btnHapusTerpilih'>@lang('global.app_deleteselected')</button>

                <span class="float-right">
                    <div id='pagination'>
                        {!! $table['pagination'] !!}
                    </div>
                </span>
            </div>
        </div>
    </div>

    @include('admin.master.divisi.modal')

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

        function PilihSemuaData() {

            var chk;
            if(document.getElementById('PilihSemuaData').checked){
                $chk =  true;
            }else{
                $chk =  false;
            }

            var pilih = document.getElementsByName("ids[]");
            var jml=pilih.length;

            var b=0;
            for (b=0;b<jml;b++)
            {
                pilih[b].checked=$chk;
            }
        }

        function BuatBaru(){
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'>");
            $('#modal-title').html("Buat Data Divisi");
            $('#pesan').html("");
            $('#deskripsi').val("");

            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function EditData(id, deskripsi, uraian, warna){
            $('#pesan').html("");
            $('#modal-title').html("Edit Data Divisi");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#deskripsi').val(deskripsi);

            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function HapusData(id){

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&id=" + id + "&halaman=" + halaman_aktif ;

            Swal.fire({
                text: 'Apakah yakin data akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.master.divisi.hapusdata') }}',
                        type: 'POST',
                        data: datanya
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
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);
                    $('#pagination2').html(data.pagination);

                    Swal.fire({
                        icon: 'success',
                        text: data.pesan,
                        timer: 2000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })

                }

            })
        }

        function HapusTerpilih(){
            var ids = [];

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif ;


            $("input[name='ids[]']:checked").each(function ()
            {
                datanya += "&ids[]="+parseInt($(this).val());
            });

            Swal.fire({
                text: 'Apakah yakin data yang dipilih akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route('admin.master.divisi.hapusdipilih') }}',
                        type: 'POST',
                        data:  datanya,
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
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);
                    $('#pagination2').html(data.pagination);

                    Swal.fire({
                        icon: 'success',
                        text: pesan,
                        timer: 3000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })
                }
            })

            return false;
        }

        function LoadData(halaman) {
            Swal.showLoading();

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman ;


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.divisi.loaddatatable') }}',
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
                            text: 'Load data gagal'
                        })
                    }
                }
            })

        }

        function SimpanData(){

            var deskripsi = document.getElementById("deskripsi");
            if (deskripsi.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Nama divisi belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#deskripsi").focus();
                                },
                        })
                return;
            }

            var datanya = $('#form_data').serialize() + "&" + $('#form_filter').serialize() ;

            datanya = datanya + "&halaman=" + halaman_aktif;

            Swal.fire({
                text: 'Apakah yakin data sudah benar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.master.divisi.store') }}',
                        type: 'POST',
                        data:  datanya
                    })
                    .then(response => {
                        return response
                    })
                    // .catch(error => {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         text: "Error"
                    //     })
                    // })
                },
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);
                    $('#pagination2').html(data.pagination);
                    $('#TambahData').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        text: pesan,
                        timer: 3000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })
                }
            })

            return false;
        }



    </script>
@endsection
