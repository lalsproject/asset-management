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
                    <div class="col-md-6 form-group">
                        <p><b>Barang</b></p>
                        <input type="text" class="form-control" id='barang' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Cabang</b></p>
                        <input type="text" class="form-control" id='cabang' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan_pengajuan' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Created</b></p>
                        <input type="text" class="form-control" id='created' disabled>
                    </div>

                </div>



                <div class="row">
                    <div class="col-md-6 form-group"  id='row1'>
                        <p><b>Kode Aset</b></p>
                        <input type="text" class="form-control" id='kode' name='kode' placeholder='Kode', maxlength=30>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>QR</b></p>
                        <input type="text" class="form-control" id='qr' name='qr' placeholder='QR Code', maxlength=30>
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
                        <input type="date" class="form-control" id='pengadaan' name='pengadaan' placeholder='Nomor Seri'>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Tanggal Input</b></p>
                        <input type="date" class="form-control" id='tgl_input' name='tgl_input' placeholder='Tanggal Input'>
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

                    <button type="button" id="btnSimpanData" class="btn btn-primary" onclick="SimpanData()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>


    <div class="modal fade" id="ModalReject">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-reject">Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_reject']) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <p><b>Barang</b></p>
                        <input type="text" class="form-control" id='barang_reject' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Cabang</b></p>
                        <input type="text" class="form-control" id='cabang_reject' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan_reject' disabled>
                    </div>

                    <div class="col-md-6 form-group">
                        <p><b>Created</b></p>
                        <input type="text" class="form-control" id='created_reject' disabled>
                    </div>

                    
                    
                    
                    <div class="col-md-12 form-group">
                        <p><b>Keterangan</b></p>
                        <input type="text" class="form-control" id='keterangan2' name='keterangan2' placeholder='Keterangan', maxlength=200>
                    </div>
                    
                    <div id="paramhidden_reject">
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
                    <button type="button" id="btnSimpanData" class="btn btn-danger" onclick="SimpanReject()">Reject Pengajuan Aset</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>

