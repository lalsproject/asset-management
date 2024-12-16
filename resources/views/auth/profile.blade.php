@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'User Profile')

@section('content_header')
    User Profile
@stop


@section('adminlte_css_pre')

    <link rel="stylesheet" href="cropbox/style.css" type="text/css" />
    <style>
        .container
        {
            position: absolute;
            top: 10%; left: 10%; right: 0; bottom: 0;
        }
        .action
        {
            width: 400px;
            height: 30px;
            margin: 10px 0;
        }
        .cropped>img
        {
            margin-right: 10px;
        }
    </style>

    <script src="cropbox/cropbox.js"></script>



@stop


@section('content')

    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">

                            <img src="{{ Auth::user()->adminlte_image() }}"
                                 class="profile-user-img img-fluid img-circle"
                                 alt="{{ Auth::user()->name }}">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                        <p class="text-muted text-center">{{ Auth::user()->email }}</p>

                        <div class="text-center">

                            <button type="button" class="btn btn-sm btn-info" onclick="GantiFotoProfile()" id='btnGantiFotoProfile'>Ganti Foto Profile</button>
                        </div>

                    </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>


        </div> <!-- /.row -->
      </div><!-- /.container-fluid -->




        <div class="modal fade" id="ModalPP">
            <div class="modal-dialog  modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modal-title-role">Ganti Foto Profile</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body">
                    <div class='row'>
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="imageBox">
                                <div class="thumbBox"></div>
                                <div class="spinner" style="display: none"></div>
                            </div>
                            <div class="action">
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" style="float:left; width: 300px">
                                    <label class="custom-file-label" for="file">Pilih file</label>
                                  </div>
                                </div>

                                <!--
                                <input type="file" id="file" style="float:left; width: 300px">
                                <input type="button" id="btnZoomIn" value="+" style="float: right">
                                <input type="button" id="btnZoomOut" value="-" style="float: right">
                                <input type="button" id="btnCrop" value="Crop" style="float: right">
                                -->
                            </div>
                        </div>
                        <!--
                        <div class="col-sm-6">
                            <div class="cropped">

                            </div>
                        </div>
                        -->
                        <div class="col-sm-12">
                            <div id="pesanpp">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <span class='pull-left'>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </span>

                    <span class='pull-right'>
                        <i id='overlay-modal-pp' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                        <button type="button" id="btnCrop" class="btn btn-primary">@lang('global.app_save')</button>
                    </span>

                </div>
              </div>
            </div>
        </div>





    </section>
    <!-- /.content -->




@stop

@section('js') 

    <script type="text/javascript">
        window.onload = function() {
            var options =
            {
                imgSrc: '{{ Auth::user()->adminlte_image() }}',
                imageBox: '.imageBox',
                thumbBox: '.thumbBox',
                spinner: '.spinner'
            }

            var cropper = new cropbox(options);
            document.querySelector('#file').addEventListener('change', function(){
                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = new cropbox(options);
                }
                reader.readAsDataURL(this.files[0]);
                this.files = [];
            })

            document.querySelector('#btnCrop').addEventListener('click', function(){
                $('#overlay-modal-pp').show();
                $('#btnCrop').hide();

                var img = cropper.getDataURL();
                
                $.ajax({
                    url:  '{{ route('uploadimage') }}',
                    type: "POST",
                    data: {"image":img,
                            "_token":_token},
                    success: function (data) {

                        $('#overlay-modal-pp').hide();
                        $('#btnCrop').show();


                        var myObj = data;

                        var pesan = myObj.message;
                        var success = myObj.success;
                        var data = myObj.data;

                        var html = "";

                        $('#overlay-modal-pp').hide();
                        $('#btnCrop').show();

                        if (success) {
                            html = " <div class='alert alert-success alert-dismissable'>";
                            html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                            html = html +  pesan;

                            html = html +  "</div>";
                            $('#pesanpp').html(html);

                            window.location.reload();
                        } else {

                            html = " <div class='alert alert-danger alert-dismissable'>";
                            html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                            html = html +  pesan;
                            html = html +  "</div>";

                            $('#pesanpp').html(html);
                        }
                    }
                });
            })


            cropper.zoomIn();

            document.querySelector('#btnZoomIn').addEventListener('click', function(){
                cropper.zoomIn();
            })
            document.querySelector('#btnZoomOut').addEventListener('click', function(){
                cropper.zoomOut();
            })





        };
    </script>

    <script>

    function SimpanGantiPassword() {
        var password_lama = document.getElementById("password_lama");
        if (password_lama.value.trim() == "") {
            alert("Password Lama belum diisi");
            password_lama.focus();
            return;
        }

        var password_konfirmasi = document.getElementById("password_konfirmasi");
        if (password_konfirmasi.value.trim() == "") {
            alert("Password Konfirmasi belum diisi");
            password_konfirmasi.focus();
            return;
        }

        var password = document.getElementById("password");
        if (password.value.trim() == "") {
            alert("Password Baru belum diisi");
            password.focus();
            return;
        }

        if (password.value != password_konfirmasi.value) {
            alert("Password konfirmasi tidak sesuai");
            password.focus();
            return;
        }


        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlaypassword').show();

        var datanya = $("#form_data").serialize();

        alert(datanya);

        $.ajax({
            type : 'POST',
            url  : '{{ route('gantipassword') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlaypassword').hide();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  pesan;

                    html = html +  "</div>";
                    $('#pesan').html(html);
                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesan').html(html);
                }
            }
        })
        return false ;
    }

    function GantiFotoProfile() {
        $('#pesanpp').html('');
        $('#ModalPP').modal('show');
    }

    </script>


@endsection