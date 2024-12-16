<script>
    window.halaman_aktif_maintenance = 1;
    window.aset_id_maintenance = 1;
    window.aset_id_deskripsi = 1;

    function Maintenance(id){
        aset_id_maintenance = id;
        LoadDataMaintenance(1, true);
    }

    function LoadDataMaintenance(halaman, baruload = false) {
        Swal.showLoading();

        var datanya = $("#form_filter").serialize();
        datanya = datanya + "&halaman=" + halaman  + "&id=" + aset_id_maintenance ;

        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.master.aset.maintenance') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                Swal.close();

                if (success) {
                    halaman_aktif_maintenance = halaman;
                    $('#tablearea_maintenance').html(data.table);
                    $('#pagination_maintenance').html(data.pagination);
                    $('#pagination2_maintenance').html(data.pagination);
                    aset_id_deskripsi = data.aset;

                    if (baruload) {
                        $('#ModalViewMaintenance').modal('show');
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: 'Load data gagal'
                    })

                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                        icon: 'error',
                        text: 'error '+ status + ' ' + error
                    })
            }
        })


    }

    function TambahMaintenance() {

        $('#pesanmaintenance').html("");
        $('#aset_maintenance').val(aset_id_deskripsi);
        $('#aset_maintenance').prop('disabled', true);

        $('#keterangan_maintenance').val("");
        $('#vendor_maintenance').val("");
        $('#harga_maintenance').val("0");

        $('#btnSimpanDataMaintenance').show();

        $('#ModalMaintenance').modal('show');
    }

    function SimpanDataMaintenance(){

        var datanya = $("#form_data_maintenance").serialize() ;
            datanya += "&aset_id=" + aset_id_maintenance + "&halaman=" + halaman_aktif_maintenance;

            Swal.fire({
                text: 'Apakah yakin data sudah benar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.master.aset.simpanmaintenance') }}',
                        type: 'POST',
                        data:  datanya
                    })
                    .then(response => {
                        return response
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: "Error"
                        })
                    })
                },
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#tablearea_maintenance').html(data.table);
                    $('#pagination_maintenance').html(data.pagination);
                    $('#pagination2_maintenance').html(data.pagination);

                    $('#ModalMaintenance').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        text: pesan,
                        timer: 3000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })
                }
            })

            return false;

        return false ;
    }

    function HapusMaintenance(id){

        var datanya = $("#form_data_maintenance").serialize() ;
            datanya += "&aset_id=" + aset_id_maintenance + "&id=" + id + "&halaman=" + halaman_aktif_maintenance;

            Swal.fire({
                text: 'Apakah yakin data akan dihapus ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url  : '{{ route('admin.master.aset.hapusmaintenance') }}',
                        type: 'POST',
                        data:  datanya
                    })
                    .then(response => {
                        return response
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            text: "Error"
                        })
                    })
                },
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {

                var myObj = result.value;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#tablearea_maintenance').html(data.table);
                    $('#pagination_maintenance').html(data.pagination);
                    $('#pagination2_maintenance').html(data.pagination);

                    Swal.fire({
                        icon: 'success',
                        text: pesan,
                        timer: 3000
                    })

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })
                }
            })

            return false;

        return false ;
    }
</script>


