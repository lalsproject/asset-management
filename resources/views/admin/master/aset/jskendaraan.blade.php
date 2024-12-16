<script>

    function DataKendaraan(id) {

        $('#pesankendaraan').html("");
        $('#aset_kendaraan').val("");

        $('#merk_type_kendaraan').val("");
        $('#no_polisi_kendaraan').val("");
        $('#no_bpkb_kendaraan').val("0");
        $('#no_mesin_kendaraan').val("0");
        $('#no_rangka_kendaraan').val("");
        $('#tahun_pembuatan_kendaraan').val("");
        $('#asal_kendaraan').val("");
        $('#keterangan_kendaraan').val("");

        var v_url = '{{ route('admin.master.aset.ambildatakendaraan', ['id' => '-id-']) }}';
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
                    $('#paramhiddenkendaraan').html("<input type='hidden' name='aset_id' value = " + id + ">");

                    $('#aset_kendaraan').val(data.aset);
                    $('#aset_kendaraan').prop('disabled', true);

                    if (data.kendaraan != null) {

                        $('#merk_type_kendaraan').val(data.kendaraan.merk_type);
                        $('#no_polisi_kendaraan').val(data.kendaraan.no_polisi);
                        $('#no_bpkb_kendaraan').val(data.kendaraan.no_bpkb);
                        $('#no_mesin_kendaraan').val(data.kendaraan.no_mesin);
                        $('#no_rangka_kendaraan').val(data.kendaraan.no_rangka);
                        $('#tahun_pembuatan_kendaraan').val(data.kendaraan.tahun_pembuatan);
                        $('#tanggal_pembelian_kendaraan').val(data.kendaraan.tanggal_pembelian);
                        $('#berlaku_stnk_kendaraan').val(data.kendaraan.berlaku_stnk);
                        $('#asal_kendaraan').val(data.kendaraan.asal);

                        $('#keterangan_kendaraan').val(data.kendaraan.keterangan);
                    }

                    $('#overlay-modal-kendaraan').hide();
                    $('#btnSimpanDataKendaraan').show();

                    $('#MasterKendaraan').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }

    function SimpanDataKendaraan(){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlay-modal-kendaraan').show();
        $('#btnSimpanDataKendaraan').hide();

        var datanya = $("#form_data_kendaraan").serialize();
        
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.master.aset.simpandatakendaraan') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal-kendaraan').hide();
                $('#btnSimpanDataKendaraan').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  pesan;

                    html = html +  "</div>";
                    $('#pesankendaraan').html(html);

                    $('#MasterKendaraan').modal('hide');

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

                    $('#pesankendaraan').html(html);

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


