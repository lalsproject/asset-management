@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Aset')
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/rasata/table.css') }}">
@stop

@section('content_header')
    Master Aset
@stop

@section('content')



    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>

        <div class="card-body">

            {!! Form::open(['method' => 'POST', 'id' => 'form_filter', 'route' => ['admin.master.aset.exportexcel']]) !!}

            <div class='row'>
                <div class='col-lg-6'>
                    <div class="form-group">
                        <label>Lokasi</label>
                        {!! Form::select('filter_lokasi[]', $lokasi, [], ['id' => 'filter_lokasi', 'class' => 'form-control select2', 'style' => 'width: 100%','multiple']) !!}
                    </div>
                </div>

                <div class='col-lg-6'>
                    <div class="form-group">
                        <label>Kriteria</label>
                        <input type="text" class="form-control" id="kriteria" name="kriteria" placeholder="@lang('global.app_search')">


                    </div>
                </div>

            </div>

        </div>
        <div class="card-footer">
            <div class='row'>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" onclick="LoadData(1)">Load Data</button>
                    {!! Form::submit("Export Excel", ['class' => 'btn btn-success']) !!}

                    {!! Form::close() !!}

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

            <div id='tablearea'  class="tableFixHead">
                {!! $table['table'] !!}
            </div>
        </div>

        <div class="card-footer">
            <div class='row'>
                <div class="col-md-12">
                    <button type="button" class="btn btn-xs btn-danger" onclick="HapusTerpilih()" id='btnHapusTerpilih'>@lang('global.app_deleteselected')</button>
                    <span class="float-right">
                        <div id='pagination2'>
                            {!! $table['pagination'] !!}
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>

    @include('admin.master.aset.modal')
@stop

@section('js')
    <script>
        window.halaman_aktif = 1;

        $('#form_data').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            e.preventDefault();
            return false;
          }
        });
        $('#form_filter').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            LoadData(1);
            return false;
          }
        });


        $('#kriteria').on('keyup', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            LoadData(1);
            return false;
          }
        });

        $("#barang_id").select2({
            dropdownParent: $("#TambahData")
        });

        $("#lokasi_id").select2({
            dropdownParent: $("#TambahData")
        });

        $('#barang_id').on('select2:select', function (e) {

            var barang_id = $('#barang_id').val();

            var v_url = '{{ route('admin.master.barang_sub.ambildata', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', barang_id);

            Swal.showLoading();

            $('#barang_sub_id').next(".select2-container").hide();
            $('#overlay_barang_sub').show();

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rdata) {
                    var pesan = rdata.message;
                    var success = rdata.success;
                    var data = rdata.data;

                    $('#barang_sub_id').next(".select2-container").show();
                    $('#overlay_barang_sub').hide();
                    Swal.close();

                    if (success) {
                        $('select[name="barang_sub_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="barang_sub_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Ambil data Gagal',
                            position: 'bottomRight',
                            autohide: true,
                            delay: 4000,
                            body: pesan
                        })
                    }
                }
            })

            return false;
        });


        $('#lokasi_id').on('select2:select', function (e) {

            var lokasi_id = $('#lokasi_id').val();

            var v_url = '{{ route('admin.master.ruang.ambildata', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', lokasi_id);

            Swal.showLoading();

            $('#ruang_id').hide();
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
                    $('#ruang_id').show();
                    $('#overlay_ruang').hide();

                    if (success) {
                        $('select[name="ruang_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="ruang_id"]').append('<option value="'+ key +'">'+ value +'</option>');
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
            $('#modal-title').html("Buat Data Aset");
            $('#pesan').html("");
            $('#kode').val("");
            $('#qr').val("");
            $('#tipe').val("");
            $('#seri').val("");
            $('#harga').val("0");
            $('#jumlah_susut').val("1");
            $('#keterangan').val("");
            $('#supplier').val("");
            $('#pengguna').val("");

            $('#barang_id').prop('disabled', false);
            $('#barang_sub_id').prop('disabled', false);
            $('#lokasi_id').prop('disabled', false);
            $('#ruang_id').prop('disabled', false);

            $('#row1').hide();
            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function EditData(id){
            Swal.showLoading();

            $('#pesan').html("");
            $('#modal-title').html("Edit Data Aset");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#kode').val("");
            $('#qr').val("");
            $('#tipe').val("");
            $('#seri').val("");
            $('#harga').val("0");
            $('#jumlah_susut').val("1");
            $('#keterangan').val("");
            $('#supplier').val("");
            $('#pengguna').val("");


            $('#row1').show();
            $('#overlay-modal').hide();
            $('#btnSimpanData').show();


            var v_url = '{{ route('admin.master.aset.edit', ['aset' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    if (success) {

                        $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                        $('#barang_id').val(data.barang);
                        $('#barang_id').change();

                        var barang_sub = data.barang_sub;
                        $('select[name="barang_sub_id"]').empty();
                        $.each(barang_sub, function(key, value) {
                            $('select[name="barang_sub_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                        $('#lokasi_id').val(data.lokasi);
                        $('#lokasi_id').change();

                        var ruang = data.ruang;
                        $('select[name="ruang_id"]').empty();
                        $.each(ruang, function(key, value) {
                            $('select[name="ruang_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                        $('#barang_sub_id').val(data.aset.barang_sub_id);
                        $('#barang_sub_id').change();

                        $('#ruang_id').val(data.aset.ruang_id);
                        $('#ruang_id').change();


                        $('#jenis_pengadaan_id').val(data.aset.jenis_pengadaan_id);
                        $('#jenis_pengadaan_id').change();


                        $('#divisi_id').val(data.aset.divisi_id);
                        $('#divisi_id').change();

                        $('#status_id').val(data.aset.status_id);
                        $('#status_id').change();

                        $('#jenis_id').val(data.aset.jenis_id);
                        $('#jenis_id').change();

                        $('#kondisi_id').val(data.aset.kondisi_id);
                        $('#kondisi_id').change();

                        $('#tipe').val(data.aset.tipe);
                        $('#seri').val(data.aset.seri);
                        $('#pengadaan').val(data.aset.pengadaan);
                        $('#tgl_input').val(data.aset.tgl_input);
                        $('#harga').val(data.aset.harga);
                        $('#jumlah_susut').val(data.aset.jumlah_susut);
                        $('#keterangan').val(data.aset.keterangan);
                        $('#supplier').val(data.aset.supplier);
                        $('#pengguna').val(data.aset.pengguna);

                        $('#kode').val(data.aset.kode);
                        $('#kode').prop('disabled', true);

                        
                        $('#barang_id').prop('disabled', true);
                        $('#barang_sub_id').prop('disabled', true);
                        $('#lokasi_id').prop('disabled', true);
                        $('#ruang_id').prop('disabled', true);
                        
                        $('#overlay-modal').hide();
                        $('#btnSimpanData').show();

                        Swal.close();

                        $('#TambahData').modal('show');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: "Ambil data gagal"
                        })

                    }
                }
            })


        }

        function HapusData(id){
            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif  + "&id=" + id ;

            Swal.fire({
                text: 'Apakah yakin data akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.master.aset.hapusdata') }}',
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

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;

            $("input[name='ids[]']:checked").each(function ()
            {
                datanya += "&ids[]=" + parseInt($(this).val()) ;
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
                        url: '{{ route('admin.master.aset.hapusdipilih') }}',
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

            // var kriteria = document.getElementById("kriteria").value;
            // if (kriteria.trim() != "") {
            //     datanya = datanya + "&kriteria=" + kriteria;
            // }
            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.aset.loaddatatable') }}',
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

        function SimpanData(){
            var pengadaan = document.getElementById("pengadaan");
            if (pengadaan.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Tanggal pengadaan belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#pengadaan").focus();
                                },
                        })
                return;
            }

            var tgl_input = document.getElementById("tgl_input");
            if (tgl_input.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Tanggal input belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#tgl_input").focus();
                                },
                        })
                return;
            }

            var harga = document.getElementById("harga");
            if (harga.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Harga belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#harga").focus();
                                },
                        })
                return;

            }

            var jumlah_susut = document.getElementById("jumlah_susut");
            if (jumlah_susut.value.trim() == "") {
                Swal.fire({
                            icon: 'error',
                            text: "Lama penyusutan aset belum diisi",
                            closeOnConfirm: true,
                            onAfterClose: (e) => {
                                   $("#jumlah_susut").focus();
                                },
                        })
                return;
            }

            // var confirmation = confirm(" @lang('global.app_confirm') ");
            // if (!confirmation) {
            //     return false;
            // }

            // $('#overlay-modal').show();
            // $('#btnSimpanData').hide();

            var datanya = $("#form_data").serialize() + "&" + $("#form_filter").serialize();;
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
                        url  : '{{ route('admin.master.aset.store') }}',
                        type: 'POST',
                        data:  datanya
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

        function Image(id) {

            // alert(id);

            // var v_url = '{{ url('admin.master.ruang.ambildata', ['id' => '-id-']) }}';
            var v_url = '{{ url(env('APP_URL').env('APP_URL_IMG').'aset/aset_-id-.jpg') }}';
            v_url = v_url.replace('-id-', id);

            // alert(v_url);
            $('#area_image').html("<img class='img-fluid' src='" + v_url + "'>");

            $('#ModalImage').modal('show');


        }
    </script>


    @include('admin.master.aset.jstanah')
    @include('admin.master.aset.jskendaraan')
    @include('admin.master.aset.jsmaintenance')

@endsection
