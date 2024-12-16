@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Approval Pengajuan Toko')

@section('content_header')
    Approval Pengajuan Toko
@stop

@section('content')



<div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>

        <div class="card-body">

            {!! Form::open(['method' => 'POST', 'id' => 'form_filter', 'route' => ['admin.approval.pengajuantoko.exportexcel']]) !!}

            <div class='row'>
                <div class='col-lg-6'>
                    <div class="form-group">
                        <label>Status</label>
                        {!! Form::select('filter_status[]', $status_pengajuan, [0], ['id' => 'filter_status', 'class' => 'form-control select2', 'style' => 'width: 100%','multiple']) !!}
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

    @include('admin.approval.pengajuantoko.modal')
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

            
            $('#barang_sub_id').hide();
            $('#overlay_barang_sub').show();

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(rdata) {
                    var pesan = rdata.message;
                    var success = rdata.success;
                    var data = rdata.data;

                    $('#barang_sub_id').show();
                    $('#overlay_barang_sub').hide();

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

                    $('#ruang_id').show();
                    $('#overlay_ruang').hide();

                    if (success) {
                        $('select[name="ruang_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="ruang_id"]').append('<option value="'+ key +'">'+ value +'</option>');
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

        function Detail(id){
            $('#pesan').html("");
            $('#modal-title').html("Detail Pengajuan Toko");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
          

            var v_url = '{{ route('admin.approval.pengajuantoko.edit', ['pengajuantoko' => '-id-']) }}';
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

                        $('#barang').val(data.pengajuan.produk_id + " " + data.pengajuan.deskripsi);
                        $('#cabang').val(data.pengajuan.cabang + " " + data.pengajuan.cabang_nama);
                        $('#keterangan_pengajuan').val(data.pengajuan.keterangan );
                        $('#created').val(data.pengajuan.created );


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

                        if (data.aset == null) {
                            $('#jenis_pengadaan_id').val("");
                            $('#jenis_pengadaan_id').change();
    
    
                            $('#divisi_id').val("");
                            $('#divisi_id').change();
    
                            $('#status_id').val("");
                            $('#status_id').change();
    
                            $('#jenis_id').val("");
                            $('#jenis_id').change();
    
                            $('#kondisi_id').val("");
                            $('#kondisi_id').change();
    
                            $('#tipe').val("");
                            $('#seri').val("");
                            $('#pengadaan').val("");
                            $('#tgl_input').val("{!! Date('Y-m-d') !!}");
                            $('#harga').val(data.pengajuan.harga);
                            $('#jumlah_susut').val(1);
                            $('#keterangan').val(data.pengajuan.keterangan);
                            $('#supplier').val("");
                            $('#pengguna').val("");
    
                            $('#kode').val("");

                            $('#qr').val("");

                            $('#barang_id').prop('disabled', false);
                            $('#barang_sub_id').prop('disabled', false);
                            $('#lokasi_id').prop('disabled', false);
                            $('#ruang_id').prop('disabled', false);

                            $('#jenis_pengadaan_id').prop('disabled', false);
                            $('#divisi_id').prop('disabled', false);
                            $('#status_id').prop('disabled', false);
                            $('#jenis_id').prop('disabled', false);
                            $('#kondisi_id').prop('disabled', false);
                            $('#seri').prop('disabled', false);
                            $('#pengadaan').prop('disabled', false);
                            $('#tgl_input').prop('disabled', false);
                            $('#harga').prop('disabled', false);
                            $('#jumlah_susut').prop('disabled', false);
                            $('#keterangan').prop('disabled', false);
                            $('#supplier').prop('disabled', false);
                            $('#pengguna').prop('disabled', false);
                            $('#qr').prop('disabled', false);
                            $('#kode').prop('disabled', true);
                            
                            $('#btnSimpanData').show();



                        } else {

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
                            
                            $('#qr').val(data.aset.qr);


                            $('#barang_id').prop('disabled', true);
                            $('#barang_sub_id').prop('disabled', true);
                            $('#lokasi_id').prop('disabled', true);
                            $('#ruang_id').prop('disabled', true);

                            $('#jenis_pengadaan_id').prop('disabled', true);
                            $('#divisi_id').prop('disabled', true);
                            $('#status_id').prop('disabled', true);
                            $('#jenis_id').prop('disabled', true);
                            $('#kondisi_id').prop('disabled', true);
                            $('#seri').prop('disabled', true);
                            $('#pengadaan').prop('disabled', true);
                            $('#tgl_input').prop('disabled', true);
                            $('#harga').prop('disabled', true);
                            $('#jumlah_susut').prop('disabled', true);
                            $('#keterangan').prop('disabled', true);
                            $('#supplier').prop('disabled', true);
                            $('#pengguna').prop('disabled', true);
                            $('#kode').prop('disabled', true);
                            $('#qr').prop('disabled', true);
                            
                            $('#btnSimpanData').hide();
 

                        }





                        $('#TambahData').modal('show');

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


        }

        function Reject(id){
            $('#modal-title-reject').html("Reject Pengajuan Toko");

            var v_url = '{{ route('admin.approval.pengajuantoko.edit', ['pengajuantoko' => '-id-']) }}';
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

                        $('#paramhidden_reject').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                        $('#barang_reject').val(data.pengajuan.produk_id + " " + data.pengajuan.deskripsi);
                        $('#cabang_reject').val(data.pengajuan.cabang + " " + data.pengajuan.cabang_nama);
                        $('#keterangan_reject').val(data.pengajuan.keterangan );
                        $('#created_reject').val(data.pengajuan.created );

                        $('#keterangan2').val("");



                        $('#ModalReject').modal('show');

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






        }

        function LoadData(halaman) {

            Swal.showLoading();
 
            var datanya = $("#form_filter").serialize();

            datanya += "&halaman=" + halaman;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.approval.pengajuantoko.loaddatatable') }}',
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

        function SimpanReject(){
            var keterangan2 = document.getElementById("keterangan2");
            if (keterangan2.value.trim() == "") {
                Swal.fire({
                    icon: 'error',
                    text: "Alasan Reject belum diisi. "
                })

                keterangan2.focus();
                return;
            }


            var datanya = $("#form_data_reject").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;
            datanya = datanya + "&" + $("#form_filter").serialize();;
            

            
            Swal.fire({
                text: 'Apakah yakin data sudah benar ?',
                icon: 'question',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya. Sudah benar',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route('admin.approval.pengajuantoko.reject') }}',
                        type: 'POST',
                        data: datanya
                    })
                    .then(response => {
                        return response
                    })
                    // .catch(error => {
                    //  //console.log(error); // Nice to view which properties 'error' have
                    //  swal.showValidationError(
                    //      `An error ocurred: ${error.status}`
                    //  )
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
                
                    // toastr.success(data.pesan);
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

            return false ;
        }

        function SimpanData(){
            var pengadaan = document.getElementById("pengadaan");
            if (pengadaan.value.trim() == "") {
                alert("Tanggal Pengadaan belum diisi");
                pengadaan.focus();
                return;
            }

            var tgl_input = document.getElementById("tgl_input");
            if (tgl_input.value.trim() == "") {
                alert("Tanggal input aset belum diisi");
                tgl_input.focus();
                return;
            }

            var harga = document.getElementById("harga");
            if (harga.value.trim() == "") {
                alert("Harga aset belum diisi");
                harga.focus();
                return;
            }

            var jumlah_susut = document.getElementById("jumlah_susut");
            if (jumlah_susut.value.trim() == "") {
                alert("Lama Penyusutan aset belum diisi");
                jumlah_susut.focus();
                return;
            }


            var datanya = $("#form_data").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;
            datanya = datanya + "&" + $("#form_filter").serialize();;
            

            
            Swal.fire({
                text: 'Apakah yakin data sudah benar ?',
                icon: 'question',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya. Sudah benar',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route('admin.approval.pengajuantoko.store') }}',
                        type: 'POST',
                        data: datanya
                    })
                    .then(response => {
                        return response
                    })
                    // .catch(error => {
                    //  //console.log(error); // Nice to view which properties 'error' have
                    //  swal.showValidationError(
                    //      `An error ocurred: ${error.status}`
                    //  )
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
                
                    // toastr.success(data.pesan);
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

            return false ;
        }


    </script>
@endsection