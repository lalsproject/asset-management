@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master Device')

@section('content_header')
    Master Device
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

    @include('admin.master.device.modal')

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
            $('#modal-title').html("Buat Data Device");
            $('#pesan').html("");
            $('#deskripsi').val("");

            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function EditData(id, deskripsi){
            $('#pesan').html("");
            $('#modal-title').html("Edit Data Device");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#deskripsi').val(deskripsi);

            $('#overlay-modal').hide();
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
                        url  : '{{ route('admin.master.device.hapusdata') }}',
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
                        url: '{{ route('admin.master.device.hapusdipilih') }}',
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
                url  : '{{ route('admin.master.device.loaddatatable') }}',
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
                            text: "Nama device belum diisi",
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
                        url  : '{{ route('admin.master.device.store') }}',
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


        function Aktifkan(id, deskripsi){

            $('#modal-title-aktifkan').html(deskripsi);
            $("#imagearea").html("");
            
            var v_url = '{{ route('admin.master.device.aktifkan', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(myObj) {

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    if (success) {
                        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

                        var decodedString = Base64.decode(data);
                        $("#imageareaqr").html(decodedString);
                        $('#Aktifkan').modal('show');

                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger', 
                            title: 'Aktifkan device Gagal',
                            position: 'bottomRight',
                            autohide: true,
                            delay: 4000,
                            body: pesan
                        })

                    }                                
                }
            })
        }

        function Reset(id){
            if (confirm('  @lang('global.app_confirm')  ')) {
                
                $('#overlaytablearea').show();

                var datanya = "halaman=" + halaman_aktif + "&_token=" + _token + "&id=" + id;

                var kriteria = document.getElementById("kriteria").value;
                if (kriteria.trim() != "") {
                    datanya = datanya + "&kriteria=" + kriteria;
                }
                $.ajax({
                    type : 'POST',
                    url  : '{{ route('admin.master.device.reset') }}',
                    data : datanya,
                    success: function(rv) {
                        var myObj = rv;

                        var pesan = myObj.message;
                        var success = myObj.success;
                        var data = myObj.data;

                        var html = "";
                      
                        $('#overlaytablearea').hide();

                        if (success) {
                            $('#tablearea').html(data.table);
                            $('#pagination').html(data.pagination);

                        } else {
                            $(document).Toasts('create', {
                                class: 'bg-danger', 
                                title: 'Load data Gagal',
                                position: 'bottomRight',
                                autohide: true,
                                delay: 4000,
                                body: pesan
                            })
                        }
                    }
                })   
            }
            return false;
        }



    </script>
@endsection
