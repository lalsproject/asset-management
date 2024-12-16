@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('title', 'General Setting')

@section('content_header')
    General Setting
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Notifikasi</h3>
                </div>

                <div class="card-body">
                    {!! Form::open([ 'id' => 'form_data_notifikasi']) !!}

                    <div class='row'>
                        <div class="col-md-12 form-group">
                            <p><b>Pengajuan Aset Toko</b></p>
                            <input type="text" class="form-control" id='notifikasi_pengajuan_aset' name='notifikasi_pengajuan_aset' value={!! $notifikasi["pengajuan_aset"] !!} >
                        </div>

                        
                    </div>
                    {!! Form::close() !!}

                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="SimpanDataNotifikasi()">Simpan</button> 
                </div>
            </div>
        </div>



    </div>

@stop

@section('js') 
    <script>
        function SimpanDataNotifikasi(){

           var datanya = $("#form_data_notifikasi").serialize();

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
                       url: '{{ route('admin.setting.store') }}',
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
                   
                   Swal.fire({
                       icon: 'success',
                       text: pesan,
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