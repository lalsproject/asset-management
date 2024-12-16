    <div class="modal fade" id="TambahData">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Jenis Maintenance</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data']) !!}
                <div class="row">
                    <div class="col-md-12 form-group" id='nama_role'>
                        <p><b>Nama Jenis Maintenance</b></p>
                        <input type="text" class="form-control" id='deskripsi' name='deskripsi' placeholder='Nama Jenis Maintenance', maxlength=30>
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
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

