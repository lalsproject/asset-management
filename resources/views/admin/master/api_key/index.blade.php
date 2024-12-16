@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Master API Key')

@section('content_header')
    Master API Key
@stop

@section('content')

    <p>
            <button type="button" class="btn btn-success" onclick="BuatBaru()" id='btnBuatBaru'>@lang('global.app_create')</button>
    </p>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_list')</h3>

        </div>

        <div class="card-body">

            <div class='row'>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="kriteria" placeholder="@lang('global.app_search')">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" onclick="LoadData(1)"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-9">
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

        <div class="overlay" id="overlaytablearea"  style="display:none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-footer">
            <button type="button" class="btn btn-xs btn-danger" onclick="HapusTerpilih()" id='btnHapusTerpilih'>@lang('global.app_deleteselected')</button>
        </div>
    </div>

    @include('admin.master.api_key.modal')
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
            $('#modal-title').html("Buat Data API Key");
            $('#deskripsi').val("");
            $('#token').val("");
            GenerateToken();

            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function EditData(id, deskripsi, token){
            $('#pesan').html("");
            $('#modal-title').html("Edit Data API Key");
            $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = "+id+">");
            $('#token').val(token);
            $('#deskripsi').val(deskripsi);

            $('#overlay-modal').hide();
            $('#btnSimpanData').show();

            $('#TambahData').modal('show');
        }

        function HapusData(id){

            var datanya = "halaman=" + halaman_aktif + "&_token=" + _token + "&id=" + id;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }

            Swal.fire({
                text: 'Apakah yakin data akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya. Hapus saja',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route('admin.master.api_key.hapusdata') }}',
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
        }

        function HapusTerpilih(){
            var ids = [];

            $("input[name='ids[]']:checked").each(function ()
            {
                ids.push(parseInt($(this).val()));
            });

            var kriteria = document.getElementById("kriteria").value;

            Swal.fire({
                text: 'Apakah yakin data akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya. Hapus saja',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route('admin.master.api_key.hapusdipilih') }}',
                        type: 'POST',
                        data:   {
                                    _token: _token,
                                    halaman: halaman_aktif,
                                    kriteria: kriteria,
                                    ids: ids
                                }
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

            var datanya = "halaman=" + halaman + "&_token=" + _token;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }
            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.master.api_key.loaddatatable') }}',
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

                    } else {

                        Swal.fire({
                            icon: 'error',
                            text: pesan
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
                    text: "Nama API Key belum diisi. "
                })

                deskripsi.focus();
                return;
            }
            var token = document.getElementById("token");
            if (token.value.trim() == "") {
                Swal.fire({
                    icon: 'error',
                    text: "Token belum diisi. "
                })
                token.focus();
                return;
            }

            var datanya = $("#form_data").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }

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
                        url: '{{ route('admin.master.api_key.store') }}',
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

        
        function QR(id){
            Swal.showLoading();
            $("#imagearea").html("");
            
            var v_url = '{{ route('admin.master.api_key.qr', ['id' => '-id-']) }}';
            v_url = v_url.replace('-id-', id);

            $.ajax({
                type : 'GET',
                url  : v_url,
                data : '',
                success: function(myObj) {
                    Swal.close();
                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    if (success) {
                        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

                        var decodedString = Base64.decode(data);
                        $("#imageareaqr").html(decodedString);
                        $('#mdlQr').modal('show');

                    } else {

                        Swal.fire({
                            icon: 'error',
                            text: pesan
                        })

                    }                                
                }
            })
        }

        function GenerateToken() {
            var token = makeid(70);

            $('#token').val(token);
        }

        function makeid(length) {
           var result           = '';
           var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
           var charactersLength = characters.length;
           for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
           }
           return result;
        }

    </script>
@endsection
