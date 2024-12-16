    <div class="modal fade" id="TambahData">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Sub Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 form-group">
                      <label>Lokasi</label>
                      <div class="input-group input-group-sm">
                          {!! Form::select('lokasi_id', $lokasi, array_key_first($lokasi), ['id' => 'lokasi_id', 'class' => 'form-control select2bs4', 'onchange'=>"GantiLokasi()"]) !!}
                          <span class="input-group-append">
                              <button type="button" title="Tambahkan semua aset dilokasi ini" class="btn btn-info btn-flat" onclick="TambahLokasi()"><i class="fas fa-plus"></i></button>
                          </span>
                      </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Ruang</label>
                        <div class="input-group input-group-sm">
                          {!! Form::select('ruang_id', $ruang, array_key_first($ruang), ['id' => 'ruang_id', 'class' => 'form-control select2bs4', 'onchange'=>"GantiRuang()"]) !!}
                          <span class="input-group-append">
                              <button type="button" title="Tambahkan semua aset di ruangan ini" class="btn btn-info btn-flat" onclick="TambahRuang()"><i class="fas fa-plus"></i></button>
                          </span>
                      </div>
                    </div>

                    <div class="col-md-12 form-group" id='table_tambah_data'>
                    <div class='row'>
                      <div class="col-md-12">
                          <span class="float-right">
                              <div id='pagination_tambah'>
                                  {!! $table_tambah['pagination'] !!}
                              </div>
                          </span>
                      </div>
                  </div>

                  <br>


                  <div id='tablearea_tambah'  class="tableFixHead">
                      {!! $table_tambah['table'] !!}
                  </div>
              </div>


              <div class="col-md-12">

                  <span class="float-right">
                      <div id='pagination_tambah2'>
                          {!! $table_tambah['pagination'] !!}
                      </div>
                  </span>
              </div>
                 
            </div>



            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>
                    <button type="button" id="btnSimpanData" class="btn btn-primary" onclick="SimpanDipilih()">Simpan Dipilih</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

