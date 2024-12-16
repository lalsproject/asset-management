<script>

    function DataTanah(id) {

        $('#pesantanah').html("");
        $('#aset').val("");
        $('#deskripsi_tanah').val("");
        $('#alamat_tanah').val("");
        $('#luas_tanah').val("0");
        $('#luas_bangunan').val("0");
        $('#no_sertifikat_tanah').val("");
        $('#jenis_sertifikat_tanah').val("");
        $('#keterangan_tanah').val("");
        $('#modal-title-tanah').html("Data Detail Tanah");

        $('#btnSimpanDataTanah').show();
        $('#btnSimpanDataBangunan').hide();


        
        var v_url = '{{ route('admin.master.aset.ambildatatanah', ['id' => '-id-']) }}';
        v_url = v_url.replace('-id-', id);

        $.ajax({
            type : 'GET',
            url  : v_url,
            data : '',
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {
                    $('#paramhiddentanah').html("<input type='hidden' name='aset_id' value = " + id + ">");

                    $('#aset').val(data.aset);
                    $('#aset').prop('disabled', true);

                    if (data.tanah != null) {
                        $('#deskripsi_tanah').val(data.tanah.deskripsi);
                        $('#alamat_tanah').val(data.tanah.alamat);
                        $('#luas_tanah').val(data.tanah.luas_tanah);
                        $('#luas_bangunan').val(data.tanah.luas_bangunan);
                        $('#no_sertifikat_tanah').val(data.tanah.no_sertifikat);
                        $('#jenis_sertifikat_tanah').val(data.tanah.jenis_sertifikat);
                        $('#keterangan_tanah').val(data.tanah.keterangan);
                    }

                    $('#overlay-modal-tanah').hide();
                    $('#btnSimpanDataTanah').show();

                    $('#MasterTanah').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }

    function SimpanDataTanah(){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlay-modal-tanah').show();
        $('#btnSimpanDataTanah').hide();

        var datanya = $("#form_data_tanah").serialize();
        
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.master.aset.simpandatatanah') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal-tanah').hide();
                $('#btnSimpanDataTanah').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  pesan;

                    html = html +  "</div>";
                    $('#pesantanah').html(html);

                    $('#MasterTanah').modal('hide');

                    $(document).Toasts('create', {
                        class: 'bg-success', 
                        title: 'Simpan data Berhasil',
                        position: 'bottomRight',
                        autohide: true,
                        delay: 4000,
                        body: pesan
                    })


                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesantanah').html(html);

                    $(document).Toasts('create', {
                        class: 'bg-danger', 
                        title: 'Simpan data Gagal',
                        position: 'bottomRight',
                        autohide: true,
                        delay: 4000,
                        body: pesan
                    })

                }
            }
        })
        return false ;
    }


    function DataBangunan(id) {

        console.log("data bangunan");

        $('#pesantanah').html("");
        $('#aset').val("");
        $('#deskripsi_tanah').val("");
        $('#alamat_tanah').val("");
        $('#luas_tanah').val("0");
        $('#luas_bangunan').val("0");
        $('#no_sertifikat_tanah').val("");
        $('#jenis_sertifikat_tanah').val("");
        $('#keterangan_tanah').val("");
        $('#modal-title-tanah').html("Data Detail Bangunan");

        $('#btnSimpanDataTanah').hide();
        $('#btnSimpanDataBangunan').show();


        var v_url = '{{ route('admin.master.aset.ambildatabangunan', ['id' => '-id-']) }}';
        v_url = v_url.replace('-id-', id);

        $.ajax({
            type : 'GET',
            url  : v_url,
            data : '',
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {
                    $('#paramhiddentanah').html("<input type='hidden' name='aset_id' value = " + id + ">");

                    $('#aset').val(data.aset);
                    $('#aset').prop('disabled', true);

                    if (data.tanah != null) {
                        $('#deskripsi_tanah').val(data.tanah.deskripsi);
                        $('#alamat_tanah').val(data.tanah.alamat);
                        $('#luas_tanah').val(data.tanah.luas_tanah);
                        $('#luas_bangunan').val(data.tanah.luas_bangunan);
                        $('#no_sertifikat_tanah').val(data.tanah.no_sertifikat);
                        $('#jenis_sertifikat_tanah').val(data.tanah.jenis_sertifikat);
                        $('#keterangan_tanah').val(data.tanah.keterangan);
                    }

                    $('#overlay-modal-tanah').hide();
                    $('#btnSimpanDataBangunan').show();

                    $('#MasterTanah').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }


    function SimpanDataBangunan(){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlay-modal-tanah').show();
        $('#btnSimpanDataBangunan').hide();

        var datanya = $("#form_data_tanah").serialize();
        
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.master.aset.simpandatabangunan') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal-tanah').hide();
                $('#btnSimpanDataBangunan').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  pesan;

                    html = html +  "</div>";
                    $('#pesantanah').html(html);

                    $('#MasterTanah').modal('hide');

                    $(document).Toasts('create', {
                        class: 'bg-success', 
                        title: 'Simpan data Berhasil',
                        position: 'bottomRight',
                        autohide: true,
                        delay: 4000,
                        body: pesan
                    })


                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesantanah').html(html);

                    $(document).Toasts('create', {
                        class: 'bg-danger', 
                        title: 'Simpan data Gagal',
                        position: 'bottomRight',
                        autohide: true,
                        delay: 4000,
                        body: pesan
                    })

                }
            }
        })
        return false ;
    }


</script>


