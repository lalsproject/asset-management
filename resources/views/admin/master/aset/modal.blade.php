    <div class="modal fade" id="TambahData">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}
                <div class="row">
                    <div class="col-md-6 form-group"  id='row1'>
                        <p><b>Kode Aset</b></p>
                        <input type="text" class="form-control" id='kode' name='kode' placeholder='Kode', maxlength=30>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">

                            <p><b>Barang</b></p>
                                {!! Form::select('barang_id', $barang, old('barang'), ['id' => 'barang_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <p><b>Sub Barang</b></p>
                            <i class="fas fa-2x fa-sync-alt fa-spin" style="display:none" id="overlay_barang_sub"></i>

                            {!! Form::select('barang_sub_id', $barang_sub, old('barang_sub'), ['id' => 'barang_sub_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Lokasi</b></p>
                            {!! Form::select('lokasi_id', $lokasi, old('lokasi'), ['id' => 'lokasi_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>
                    <div class="col-md-6 form-group">
                        <p><b>Ruang</b></p>
                            <i class="fas fa-2x fa-sync-alt fa-spin" style="display:none" id="overlay_ruang"></i>
                            {!! Form::select('ruang_id', $ruang, old('ruang'), ['id' => 'ruang_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Jenis Pengadaan</b></p>
                            {!! Form::select('jenis_pengadaan_id', $jenis_pengadaan, old('jenis_pengadaan'), ['id' => 'jenis_pengadaan_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>
                    <div class="col-md-6 form-group">
                        <p><b>Divisi</b></p>
                            {!! Form::select('divisi_id', $divisi, old('divisi'), ['id' => 'divisi_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>
                    <div class="col-md-6 form-group">
                        <p><b>Status Aset</b></p>
                            {!! Form::select('status_id', $status, old('status'), ['id' => 'status_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>
                    <div class="col-md-6 form-group">
                        <p><b>Jenis Aset</b></p>
                            {!! Form::select('jenis_id', $jenis, old('jenis'), ['id' => 'jenis_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tipe</b></p>
                        <input type="text" class="form-control" id='tipe' name='tipe' placeholder='Tipe Aset', maxlength=100>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Nomor Seri</b></p>
                        <input type="text" class="form-control" id='seri' name='seri' placeholder='Nomor Seri', maxlength=100>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tanggal Pengadaan</b></p>
                        <input type="date" class="form-control" value='{!!  Date("Y-m-d") !!}' id='pengadaan' name='pengadaan' placeholder='Nomor Seri'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tanggal Input</b></p>
                        <input type="date" class="form-control" value='{!!  Date("Y-m-d") !!}' id='tgl_input' name='tgl_input' placeholder='Tanggal Input'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Harga</b></p>
                        <input type="number" class="form-control" id='harga' name='harga' placeholder='Harga Pengadaan'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Lama Penyusutan (Bulan)</b></p>
                        <input type="number" class="form-control" id='jumlah_susut' name='jumlah_susut' placeholder='Lama Penyusutan'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan' name='keterangan' placeholder='Keterangan', maxlength=200>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Kondisi</b></p>
                            {!! Form::select('kondisi_id', $kondisi, old('kondisi'), ['id' => 'kondisi_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Supplier</b></p>
                        <input type="text" class="form-control" id='supplier' name='supplier' placeholder='Supplier', maxlength=200>
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Pengguna</b></p>
                        <input type="text" class="form-control" id='pengguna' name='pengguna' placeholder='Pengguna', maxlength=200>
                    </div>

                    <div id="paramhidden">
                    </div>

                </div>

                {!! Form::close() !!}


                <div id="pesan">
                </div>


            </div>



            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanData" class="btn btn-primary" onclick="SimpanData()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
  </div>

    <div class="modal fade" id="MasterTanah">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-tanah">Data Detail Tanah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_tanah']) !!}
                <div class="row" id='row1tanah'>
                    <div class="col-md-12 form-group">
                        <p><b>Data Aset</b></p>
                        <input type="text" class="form-control" id='aset' placeholder='Aset'>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <p><b>Deskripsi Tanah / Bangunan</b></p>
                        <input type="text" class="form-control" id='deskripsi_tanah' name='deskripsi' placeholder='Deskripsi Tanah / Bangunan', maxlength=100>
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Alamat</b></p>
                        <input type="text" class="form-control" id='alamat_tanah' name='alamat' placeholder='Alamat Object', maxlength=100>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Luas Tanah</b></p>
                        <input type="number" class="form-control" id='luas_tanah' name='luas_tanah' placeholder='Luas Tanah'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Luas Bangunan</b></p>
                        <input type="number" class="form-control" id='luas_bangunan' name='luas_bangunan' placeholder='Luas Bangunan'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Nomor Sertifikat</b></p>
                        <input type="text" class="form-control" id='no_sertifikat_tanah' name='no_sertifikat' placeholder='Nomor Sertifikat', maxlength=50>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Jenis Sertifikat</b></p>
                        <input type="text" class="form-control" id='jenis_sertifikat_tanah' name='jenis_sertifikat' placeholder='Jenis Sertifikat', maxlength=50>
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan_tanah' name='keterangan' placeholder='Keterangan', maxlength=200>
                    </div>


                    <div id="paramhiddentanah">
                    </div>

                </div>

                {!! Form::close() !!}

                <div id="pesantanah">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-tanah' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanDataTanah" class="btn btn-primary" onclick="SimpanDataTanah()">@lang('global.app_save')</button>
                    <button type="button" id="btnSimpanDataBangunan" class="btn btn-primary" onclick="SimpanDataBangunan()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>




    <div class="modal fade" id="MasterKendaraan">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-kendaraan">Data Detail kendaraan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_kendaraan']) !!}
                <div class="row" id='row1kendaraan'>
                    <div class="col-md-12 form-group">
                        <p><b>Data Aset</b></p>
                        <input type="text" class="form-control" id='aset_kendaraan' placeholder='Aset'>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <p><b>Merk dan Tipe</b></p>
                        <input type="text" class="form-control" id='merk_type_kendaraan' name='merk_type' placeholder='Merk dan Tipe', maxlength=100>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>No. Polisi</b></p>
                        <input type="text" class="form-control" id='no_polisi_kendaraan' name='no_polisi' placeholder='Nomor Polisi', maxlength=15>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>No. BPKB</b></p>
                        <input type="text" class="form-control" id='no_bpkb_kendaraan' name='no_bpkb' placeholder='Nomor BPKB', maxlength="15">
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>No. Mesin</b></p>
                        <input type="text" class="form-control" id='no_mesin_kendaraan' name='no_mesin' placeholder='Nomor Mesin', maxlength="50">
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>No. Rangka</b></p>
                        <input type="text" class="form-control" id='no_rangka_kendaraan' name='no_rangka' placeholder='Nomor Rangka', maxlength="50">
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tahun Pembuatan</b></p>
                        <input type="number" class="form-control" id='tahun_pembuatan_kendaraan' name='tahun_pembuatan' placeholder='Tahun Pembuatan'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tanggal Pembelian</b></p>
                        <input type="date" class="form-control" id='tanggal_pembelian_kendaraan' name='tanggal_pembelian' >
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Berlaku STNK</b></p>
                        <input type="date" class="form-control" id='berlaku_stnk_kendaraan' name='berlaku_stnk' >
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Asal Kendaraan</b></p>
                        <input type="text" class="form-control" id='asal_kendaraan' name='asal' placeholder='Asal Kendaraan', maxlength=50>
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan_kendaraan' name='keterangan' placeholder='Keterangan', maxlength=100>
                    </div>


                    <div id="paramhiddenkendaraan">
                    </div>

                </div>

                {!! Form::close() !!}

                <div id="pesankendaraan">
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-kendaraan' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanDataKendaraan" class="btn btn-primary" onclick="SimpanDataKendaraan()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>


    <div class="modal fade" id="ModalViewMaintenance">
        <div class="modal-dialog"  style="max-width: 90%;">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-maintenance">Maintenance Aset</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">
                <div class='row'>
                    <div class="col-md-12">
                        <span class="float-right">
                            <div id='pagination_maintenance'>
                            </div>
                        </span>
                    </div>
                </div>

                <div id='tablearea_maintenance'  class="tableFixHead">
                </div>

                <div class='row'>
                    <div class="col-md-12">
                        <span class="float-right">
                            <div id='pagination2_maintenance'>
                            </div>
                        </span>
                    </div>
                </div>

            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>
                    <button type="button" class="btn btn-primary" onclick="TambahMaintenance()">Tambah Maintenance</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>


    <div class="modal fade" id="ModalMaintenance">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-maintenance">Input Maintenance Aset</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_maintenance']) !!}
                <div class="row" id='row1maintenance'>
                    <div class="col-md-12 form-group">
                        <p><b>Data Aset</b></p>
                        <input type="text" class="form-control" id='aset_maintenance' placeholder='Aset'>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <p><b>Jenis Maintenance</b></p>
                            {!! Form::select('jenis_maintenance_id', $jenis_maintenance, old('jenis_maintenance'), ['id' => 'jenis_maintenance_id', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan_maintenance' name='keterangan' placeholder='Keterangan', maxlength=200>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Vendor</b></p>
                        <input type="text" class="form-control" id='vendor_maintenance' name='vendor' placeholder='Vendor', maxlength="50">
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Harga</b></p>
                        <input type="number" class="form-control" id='harga_maintenance' name='harga' placeholder='Harga Maintenance', maxlength="15">
                    </div>



                    <div id="paramhiddenmaintenance">
                    </div>

                </div>

                {!! Form::close() !!}


                <div id="pesanmaintenance">
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-maintenance' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanDataMaintenance" class="btn btn-primary" onclick="SimpanDataMaintenance()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>


    <div class="modal fade" id="ModalImage">
        <div class="modal-dialog  modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-maintenance">Image Aset</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12 form-group" id='area_image'>


                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>



            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>
