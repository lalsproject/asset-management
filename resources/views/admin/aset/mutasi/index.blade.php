@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Mutasi Barang')

@section('content_header')
    Mutasi Barang
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
                <span class="float-right">
                    <div id='pagination'>
                        {!! $table['pagination'] !!}
                    </div>
                </span>
            </div>
        </div>
    </div>

    @include('admin.aset.mutasi.modal')

@stop

@section('js')
    <script>
        window.is_load = 0;
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

        $('#kode').on('change', function(e) {
           
            LoadKode();
        });

        $('#kode').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            LoadKode();
            return false;
          }
        });

        $('#lokasi').on('select2:select', function (e) {
            console.log("Gant Mutasi");            
            if (is_load == 1) { 
                console.log("is_load = 0")
                return; 
            }

            var lokasi_id = $('#lokasi').val();

            var v_url = '{{ route('admin.master.ruang.ambildata', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', lokasi_id);

            Swal.showLoading();

            $('#ruang').next(".select2-container").hide();
            $('#overlay_ruang').show();

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rdata) {
                    var pesan = rdata.message;
                    var success = rdata.success;
                    var data = rdata.data;

                    Swal.close();
                    $('#ruang').next(".select2-container").show();
                    $('#overlay_ruang').hide();

                    if (success) {
                        $('select[name="ruang"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="ruang"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    } else {

                        Swal.fire({
                                icon: 'error',
                                text: "Ambil data gagal. "
                            })
                    }
                }
            })

            return false;
        });

        function LoadKode() {
            if (is_load == 1) {return; }

            is_load = 1;

             $('#deskripsi').html("");

             Swal.showLoading();

             var datanya = "kode=" +  $("#kode").val() + "&_token=" + _token ;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.getaset') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";
                    is_load = 0;

                    Swal.close();
                    if (success) {
                        // halaman_aktif = halaman;
                        $('#deskripsi').html(data.namaaset);

                        is_load = 1;
                        $('#lokasi').val(data.aset.lokasi_id);
                        $('#lokasi').change();

                        $('select[name="ruang"]').empty();
                        $.each(data.aset.list_ruang, function(key, value) {
                            $('select[name="ruang"]').append('<option value="'+ value.id +'">'+ value.deskripsi +'</option>');
                        });

                        $('#ruang').val(data.aset.ruang_id);
                        $('#ruang').change();

                        is_load = 0;
                        // $('#pagination2').html(data.pagination);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: pesan
                        })
                    }
                }
            })

        }

        function BuatBaru(){
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'>");
            $('#modal-title').html("Buat Data Barang");
            $('#deskripsi').html("");
            $('#kode').val("");
            $('#deskripsi').val("");

            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function LoadData(halaman) {
            Swal.showLoading();

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman ;


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.aset.mutasi.loaddatatable') }}',
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
            var kode = document.getElementById("kode");
            if (kode.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Kode barang belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#kode").focus();
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
                        url  : '{{ route('admin.aset.mutasi.store') }}',
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
