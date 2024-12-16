<script>
    window.halaman_tambah = 1;

        function PilihSemuaDataTambah() {
            
            var chk;
            if(document.getElementById('PilihSemuaDataTambah').checked){
                $chk =  true;
            }else{
                $chk =  false;
            }

            var pilih = document.getElementsByName("ids2[]");
            var jml=pilih.length;

            var b=0;
            for (b=0;b<jml;b++)
            {
                pilih[b].checked=$chk;
            }
        }

        function GantiLokasi() {
            Swal.showLoading();

            var lokasi_id = $('#lokasi_id').val();

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&lokasi_id=" + lokasi_id ;


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.getruang') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        halaman_tambah = 1;

                        $('#ruang_id').empty();
                        for (const [key, value] of Object.entries(data.ruang)) {
                            $('#ruang_id').append('<option value="'+key+'">' + value +'</option>')
                        }
                        $('#ruang_id').val(Object.keys(data.ruang)[0]);
                        $('#ruang_id').change();

                        $('#tablearea_tambah').html(data.table.table);
                        $('#pagination_tambah').html(data.table.pagination);
                        $('#pagination_tambah2').html(data.table.pagination);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

        }

        function GantiRuang() {
            LoadDataTambah(1);
        }

        function LoadDataTambah(halaman) {
             Swal.showLoading();

            var datanya = $("#form_filter").serialize();
            datanya = datanya + "&halaman=" + halaman ;
            datanya = datanya + "&ruang_id=" + $('#ruang_id').val();

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.loaddatatable_tambah') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        halaman_tambah = halaman;
                        $('#tablearea_tambah').html(data.table);
                        $('#pagination_tambah').html(data.pagination);
                        $('#pagination_tambah2').html(data.pagination);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

        }


        function TambahDataCetak(id){
            Swal.showLoading();

            var datanya = $('#form_filter').serialize() +"&id=" + id ;
            console.log(datanya);


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.store') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#pagination2').html(data.pagination);

                        Swal.fire({
                            icon: 'success',
                            text: 'Data berhasil ditambahkan',
                            timer: 1500

                        })

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

            return false;


        }

        function SimpanDipilih() {
            Swal.showLoading();

            var datanya = $('#form_filter').serialize();
            datanya = datanya + "&halaman=" + halaman_aktif ;

            $("input[name='ids2[]']:checked").each(function ()
            {
                datanya += "&ids[]="+parseInt($(this).val());
            });


            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.storedipilih') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#pagination2').html(data.pagination);

                        Swal.fire({
                            icon: 'success',
                            text: 'Data berhasil ditambahkan',
                            timer: 1500

                        })

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

            return false;

        }

        function TambahLokasi() {
            Swal.showLoading();

            var datanya = $('#form_filter').serialize();
            datanya = datanya + "&halaman=" + halaman_aktif ;

            var lokasi_id = $('#lokasi_id').val();
            datanya = datanya + "&lokasi_id=" + lokasi_id ;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.storelokasi') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#pagination2').html(data.pagination);

                        Swal.fire({
                            icon: 'success',
                            text: 'Data berhasil ditambahkan',
                            timer: 1500

                        })

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

            return false;

        }


        function TambahRuang() {
            Swal.showLoading();

            var datanya = $('#form_filter').serialize();
            datanya = datanya + "&halaman=" + halaman_aktif ;

            var ruang_id = $('#ruang_id').val();
            datanya = datanya + "&ruang_id=" + ruang_id ;

            $.ajax({
                type : 'POST',
                url  : '{{ route('admin.transaksi.cetaklabel.storeruang') }}',
                data : datanya,
                success: function(rv) {
                    var myObj = rv;

                    var pesan = myObj.message;
                    var success = myObj.success;
                    var data = myObj.data;

                    var html = "";

                    Swal.close();
                    if (success) {
                        $('#tablearea').html(data.table);
                        $('#pagination').html(data.pagination);
                        $('#pagination2').html(data.pagination);

                        Swal.fire({
                            icon: 'success',
                            text: 'Data berhasil ditambahkan',
                            timer: 1500

                        })

                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: 'Load data gagal'
                        })
                    }
                }
            })

            return false;

        }
</script>