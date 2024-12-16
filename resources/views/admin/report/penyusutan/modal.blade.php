    <div class="modal fade" id="TambahData">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Detail Penyusutan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group" id='table_area_detail'>
                    </div>

                    {!! Form::open(['id' => 'form_data']) !!}

                    <div id='hideparam'>
                    </div>

                    {!! Form::close() !!}


                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </span>
                <span class='pull-right'>
                  <button type="button" class="btn btn-primary"  onclick="DetailSemua()">Detail Seluruhnya</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    </div>

