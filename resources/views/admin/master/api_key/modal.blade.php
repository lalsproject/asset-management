    <div class="modal fade" id="TambahData">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Device</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}
                <div class="row">
                    <div class="col-md-8 form-group" id='nama_role'>
                        <p><b>Deskripsi</b></p>
                        <input type="text" class="form-control" id='deskripsi' name='deskripsi' placeholder='Deskripsi', maxlength=50>
                    </div>
                    <div class="col-md-4 form-group">
                        <p><b>Status</b></p>
                            {!! Form::select('aktif', $status, old('aktif'), ['id' => 'aktif', 'class' => 'form-control select2', 'style' => 'width: 100%', 'required' => '']) !!}
                    </div>

                    <div class="col-md-12 form-group">
                        <p><b>Token</b></p>
                        <div class="input-group input-group-sm">

                            <input type="text" class="form-control" id='token' name='token' placeholder='Token', maxlength=70>
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" onclick="GenerateToken()"><i class="fas fa-exchange-alt"></i></button>
                            </span>
                        </div>
                    </div>

                    <div id="paramhidden">
                    </div>

                </div>

                {!! Form::close() !!}
            </div>



            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>

                <span class='pull-right'>

                    <button type="button"  style="display:none; width:130px" class="btn btn-primary disabled" id='overlay-modal'><i class="fas fa-sync-alt fa-spin"></i></button>
                    <button type="button"  style="width:130px" id="btnSimpanData" class="btn btn-primary" onclick="SimpanData()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

    <div class="modal fade" id="mdlQr">
        <div class="modal-dialog  modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">QR</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">
              <div id="imageareaqr" style="  display: table;  margin: 0 auto;">
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
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

