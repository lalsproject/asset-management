@extends('adminlte::page')

@section('title', 'Dashboard')

@section('adminlte_css_pre')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<style>
      .callout img {
            max-width: 100%;
            height: 'auto';
        }

        .act-btn{
            background:#28a745;
            border-color:#23923d;
            display: block;
            width: 60px;
            height: 60px;
            line-height: 30px;
            text-align: center;
            color: white;
            font-size: 30px;
            font-weight: bold;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            text-decoration: none;
            transition: ease all 0.3s;
            position: fixed;
            right: 30px;
            bottom:60px;
            z-index:100;

        }
        .act-btn:hover{
            background: #6fcc84;
        }
    </style>
@stop

@section('content_header')
    Dashboard
@stop

@section('content')

<section class="content">
    <!-- <button class="act-btn" onclick="App()">
        <i class="fab fa-android"></i>
    </button> -->

    <div class="container-fluid">
        <!-- Info boxes -->

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{!! number_format($susut) !!} </h3>
                        <p>Nilai Penyusutan Tahun Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-graph-down-right"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{!! number_format($sisa) !!} </h3>
                        <p>Sisa Penyusutan Hingga saat ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-graph-up-right"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{!! number_format($selesai_susut_ty) !!} </h3>
                        <p>Aset selesai penyusutan tahun ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{!! number_format($selesai_susut_tm) !!} </h3>
                        <p>Aset selesai susut hingga saat ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cube"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- <div class="row">
            <img src="{{url('/img/logodashboard.png')}}" >
        </div> -->
          <!-- /.col -->


    </div>




    <div class="modal fade" id="ModalApp">
        <div class="modal-dialog  modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modal-title">App Android</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <a href='https://hriss.pgass.win/app/HRISS.apk'>
                                    <img src="{{ url('/img/qr-code.png') }}"
                                        class="img-fluid"
                                        alt="Applikasi Android">
                                </a> <br>
                                <a href='https://aset.pgass.win/app/OASS.apk'>
                                    https://aset.pgass.win/app/OASS.apk
                                </a> <br>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer justify-content-between">
                    <span class='pull-left'>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </span>

                </div>
            </div>
        </div>
    </div>


</section>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');



    function App() {
        $('#ModalApp').modal('show');

    }
</script>
@stop
