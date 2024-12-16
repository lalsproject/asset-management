@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Jenis Aset')

@section('content_header')
    Master Jenis Aset
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

    @include('admin.master.jenis.modal')

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


        $(function () {
            $('#warna').select2({
                width: '100%',
                dropdownParent: $("#TambahData")
            })
        });

        function EditData(id, deskripsi, warna){
            $('#pesan').html("");
            $('#modal-title').html("Edit Data Jenis Aset");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#deskripsi').val(deskripsi);
            $('#warna').val(warna);
            $('#warna').change();
            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function LoadData(halaman) {
            Swal.showLoading();

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman ;


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.jenis.loaddatatable') }}',
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
            var datanya = $('#form_data').serialize() ;
            console.log(datanya);


            var deskripsi = document.getElementById("deskripsi");
            if (deskripsi.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Nama jenis belum diisi",
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
                        url  : '{{ route('admin.master.jenis.store') }}',
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
