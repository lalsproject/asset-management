    <div class="modal fade" id="TambahDataPermission">
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title-permission">Roles</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'id' => 'form_data_permission']) !!}
                <div class="row">
                    <div class="col-md-12 form-group" id='nama_role'>
                        <p><b>Nama Role</b></p>
                        <input type="text" class="form-control" id='name' name='name' placeholder='Nama Roles', maxlength=30>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                User Management
                            </div>

                            <div class="card-body">

                                @include('admin.roles.item', ['permission' => 'user_security', 'deskripsi' => 'User & Roles'])

                            </div>
                        </div>

                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Aset 
                            </div>

                            <div class="card-body">
                                @include('admin.roles.item', ['permission' => 'master_aset', 'deskripsi' => 'Master Aset'])
                                @include('admin.roles.item', ['permission' => 'hapus_aset', 'deskripsi' => 'Hapus Aset'])
                                @include('admin.roles.item', ['permission' => 'cetak_label', 'deskripsi' => 'Cetak Label'])
                                @include('admin.roles.item', ['permission' => 'aset_mutasi', 'deskripsi' => 'Mutasi Aset'])

                            </div>
                        </div>

                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Report
                            </div>

                            <div class="card-body">
                                @include('admin.roles.item', ['permission' => 'report_opname', 'deskripsi' => 'Opname Aset'])
                                @include('admin.roles.item', ['permission' => 'report_penyusutan', 'deskripsi' => 'Penyusutan Aset'])

                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                Master Data
                            </div>

                            <div class="card-body">
                                @include('admin.roles.item', ['permission' => 'master_api_key', 'deskripsi' => 'Master Api Key'])
                                @include('admin.roles.item', ['permission' => 'master_status', 'deskripsi' => 'Master Status'])
                                @include('admin.roles.item', ['permission' => 'master_jenis', 'deskripsi' => 'Master Jenis Aset'])
                                @include('admin.roles.item', ['permission' => 'master_jenis_maintenance', 'deskripsi' => 'Master Jenis Maintenance'])
                                @include('admin.roles.item', ['permission' => 'master_jenis_pengadaan', 'deskripsi' => 'Master Jenis Pengadaan'])
                                @include('admin.roles.item', ['permission' => 'master_kondisi', 'deskripsi' => 'Master Kondisi'])
                                @include('admin.roles.item', ['permission' => 'master_divisi', 'deskripsi' => 'Master Divisi'])
                                @include('admin.roles.item', ['permission' => 'master_satuan', 'deskripsi' => 'Master Satuan'])
                                @include('admin.roles.item', ['permission' => 'master_barang', 'deskripsi' => 'Master Barang'])
                                @include('admin.roles.item', ['permission' => 'master_barang_sub', 'deskripsi' => 'Master Sub Barang'])
                                @include('admin.roles.item', ['permission' => 'master_lokasi', 'deskripsi' => 'Master Lokasi'])
                                @include('admin.roles.item', ['permission' => 'master_ruang', 'deskripsi' => 'Master Ruang'])
                            </div>
                        </div>

                    </div>

                    <div id="paramhiddenpermission">
                    </div>

                </div>

                {!! Form::close() !!}


                <div id="pesanpermission">
                </div>


            </div>



            <div class="modal-footer justify-content-between">
                <span class='pull-left'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id='btnTidakPilih' onclick="TidakPilihPermission()" >@lang('global.app_selectnone')</button>
                    <button type="button" class="btn btn-success" id='btnPilihSemua' onclick="PilihSemuaPermission()" >@lang('global.app_selectall')</button>
                </span>

                <span class='pull-right'>

                    <i id='overlay-modal-permission' class="fas fa-2x fa-sync-alt fa-spin" style="display:none"></i>
                    <button type="button" id="btnSimpanDataPermission" class="btn btn-primary" onclick="SimpanDataPermission()">@lang('global.app_save')</button>
                </span>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

