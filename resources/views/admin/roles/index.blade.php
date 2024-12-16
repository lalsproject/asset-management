@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    Pengaturan Roles
@stop


@section('content')

    <p>
            <button type="button" class="btn btn-success" onclick="BuatBaru()" id='btnBuatBaru'>@lang('global.app_create')</button>
    </p>

    <div class="card card-primary">
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


            <div id='tablearea'>
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

    @include('admin.roles.modal')
@stop

@section('js') 
    <script>
        window.halaman_aktif = 1;



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


        function TidakPilihPermission(){
            var permission = document.getElementsByName("permission[]");
            var jml=permission.length;
            var b=0;
            for (b=0;b<jml;b++)
            {
                permission[b].checked=false;
                
            }
        }

        function PilihSemuaPermission(){
            var permission = document.getElementsByName("permission[]");
            var jml=permission.length;
            var b=0;
            for (b=0;b<jml;b++)
            {
                permission[b].checked=true;
                
            }
        }

        function BuatBaru(){
            TidakPilihPermission();

            $('#paramhiddenpermission').html("<input type='hidden' name='simpan' value = 'baru'>");
            $('#modal-title-permission').html("Buat Data Role");
            $('#pesanpermission').html("");
            $('#name').val("");

            $('#overlay-modal-permission').hide()
            $('#btnSimpanDataPermission').show()

            $('#TambahDataPermission').modal('show');
        }


        function EditData(id){
            Swal.showLoading();

            TidakPilihPermission();
            $('#pesanpermission').html("");
            $('#name').val("");

            var v_url = '{{ route('admin.roles.edit', ['role' => '-id-']) }}';
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

                    Swal.close();
                    if (success) {

                        $('#modal-title-permission').html("Edit Data Role");
                        $('#paramhiddenpermission').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                        $('#name').val(data.role.name);

                        $('#btnSimpanDataPermission').prop('disabled', false);

                        var permissions = data.permissions;
                        $.each(permissions, function(key, value) {
                            $('#' + value).prop('checked', true);
                        });

                        $('#overlay-modal-permission').hide()
                        $('#btnSimpanDataPermission').show()

                        $('#TambahDataPermission').modal('show');

                    } else {

                        alert(pesan);
                    }
                }
            })
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
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.roles.hapusdata') }}',
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
            var datanya = "halaman=" + halaman_aktif + "&_token=" + _token;

            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }

            var ids = [];

            $("input[name='ids[]']:checked").each(function ()
            {
                datanya = datanya + "&ids[]=" + parseInt($(this).val());
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
                        url: '{{ route('admin.roles.hapusdipilih') }}',
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
                url  : '{{ route('admin.roles.loaddatatable') }}',
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

        function SimpanDataPermission(){
            var name = document.getElementById("name");
            if (name.value.trim() == "") {
                alert("Nama Role belum diisi");
                name.focus();
                return;
            }



            var datanya = $("#form_data_permission").serialize();
            datanya = datanya + "&halaman=" + halaman_aktif;
            
            var kriteria = document.getElementById("kriteria").value;
            if (kriteria.trim() != "") {
                datanya = datanya + "&kriteria=" + kriteria;
            }


            
            Swal.fire({
                text: 'Apakah yakin data sudah benar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.roles.store') }}',
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
                    $('#TambahDataPermission').modal('hide');

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

            return false ;
        }


    </script>
@endsection