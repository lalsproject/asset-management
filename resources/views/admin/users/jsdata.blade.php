<script>



    function LoadData(halaman) {

        $('#overlaytablearea').show()

        var datanya = "halaman=" + halaman + "&_token=" + _token;

        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.loaddatatable') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlaytablearea').hide();

                if (success) {
                    halaman_aktif = halaman;
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);

                } else {

                    alert(pesan);
                }
            }
        })   

    }


    function BuatBaru(){

        $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'>");
        $('#modal-title').html("Buat Data User");
        $('#pesan').html("");
        $('#name').val("");
        $('#email').val("");
        $('#karyawan_id').val(0);
        $('#karyawan_id').change();

        GeneratePassword();

        $('#overlay-modal').hide();
        $('#btnSimpanData').show();

        $('#TambahData').modal('show');
    }

    function EditData(id){
        Swal.showLoading();

        $('#pesan').html("");
        $('#name').val("");
        $('#email').val("");
        $('#password').val("");
        $('#password2').val("");

        var v_url = '{{ route('admin.users.edit', ['user' => '-id-']) }}';
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
                    Swal.close();

                    $('#modal-title').html("Edit Data Pengguna");
                    $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#karyawan_id').val(data.karyawan_id);
                    $('#karyawan_id').change();

                    // $('#overlay-modal').hide();
                    $('#btnSimpanData').show();
                    $('#TambahData').modal('show');

                } else {
                    Swal.fire({
                        icon: 'error',
                        text: pesan
                    })
                }
            }
        })
    }

    function HapusData(id){
        var datanya = "halaman=" + halaman_aktif + "&_token=" + _token + "&id=" + id;

        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }
        Swal.fire({
            text: 'Apakah yakin data akan dihapus ?',
            icon: 'question',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya. Hapus saja',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '{{ route('admin.users.hapusdata') }}',
                    type: 'POST',
                    data: datanya
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
                
                $('#tablearea').html(data.table);
                $('#pagination').html(data.pagination);

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


    }

    function HapusTerpilih(){
        var ids = [];

        $("input[name='ids[]']:checked").each(function ()
        {
            ids.push(parseInt($(this).val()));
        });

        var kriteria = document.getElementById("kriteria").value;

        Swal.fire({
            text: 'Apakah yakin data akan dihapus ?',
            icon: 'question',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya. Hapus saja',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '{{ route('admin.users.hapusdipilih') }}',
                    type: 'POST',
                    data:   {
                                _token: _token,
                                halaman: halaman_aktif,
                                kriteria: kriteria,
                                ids: ids
                            }
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
                
                $('#tablearea').html(data.table);
                $('#pagination').html(data.pagination);

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
    }

    
    function SimpanData(){
        var name = document.getElementById("name");
        if (name.value.trim() == "") {
            alert("Nama User belum diisi");
            name.focus();
            return;
        }
        var email = document.getElementById("email");
        if (email.value.trim() == "") {
            alert("Email User belum diisi");
            email.focus();
            return;
        }


        var password = document.getElementById("password");
        var password2 = document.getElementById("password2").value.trim();
        if (password.value.trim() != password2) {
            Swal.fire({
                    icon: 'error',
                    text: "Password konfirmasi tidak sama"
                })
            password.focus();
            return;
        }

        var datanya = $("#form_data").serialize();
        datanya = datanya + "&halaman=" + halaman_aktif;
        
        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }

        Swal.fire({
            text: 'Apakah yakin data sudah benar ?',
            icon: 'question',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya. Sudah benar',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '{{ route('admin.users.store') }}',
                    type: 'POST',
                    data: datanya
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
                
                $('#tablearea').html(data.table);
                $('#pagination').html(data.pagination);

                $('#TambahData').modal('hide');
            
                Swal.fire({
                    icon: 'success',
                    text: data.pesan,
                    timer: 2000
                })

            } else {
                Swal.fire({
                    icon: 'error',
                    text: pesan
                })

            }

        })
        return false ;
    }

</script>


