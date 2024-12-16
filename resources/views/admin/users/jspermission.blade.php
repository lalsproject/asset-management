<script>

    function Permission(id){
        TidakPilihPermission();
        $('#paramhiddenpermission').html("<input type='hidden' name='user_id' value = '" + id +"'>");
        $('#pesanpermission').html("");
        $('#overlay-modal-permission').hide();
        $('#btnSimpanDataPermission').show();
        $('#nama_role').hide();

        var v_url = '{{ route('admin.users.permissions', ['user' => '-id-']) }}';
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

                    $('#modal-title-permission').html(data.name);

                    var permissions = data.permissions;
                    $.each(permissions, function(key, value) {
                        var res = value.replace(" ", "_");
                        $('#' + res).prop('checked', true);
                    });

                    $('#overlay-modal-permission').hide();
                    $('#btnSimpanDataPermission').show();

                    $('#TambahDataPermission').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }


   function TidakPilihPermission(){
        var permission = document.getElementsByName("permission[]");
        var jml=permission.length;
        var b=0;
        for (b=0;b<jml;b++)
        {
            permission[b].checked=false;
            
        }
    }

    function PilihSemuaPermission(){
        var permission = document.getElementsByName("permission[]");
        var jml=permission.length;
        var b=0;
        for (b=0;b<jml;b++)
        {
            permission[b].checked=true;
            
        }
    }


    function SimpanDataPermission(){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlay-modal-permission').show();
        $('#btnSimpanDataPermission').hide();

        var datanya = $("#form_data_permission").serialize();
        datanya = datanya + "&halaman=" + halaman_aktif;
        
        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }

        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.simpanpermissions') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal-permission').hide();
                $('#btnSimpanDataPermission').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html + "Data berhasil disimpan";

                    html = html +  "</div>";

                    $('#pesanpermission').html(html);
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);

                    $('#TambahDataPermission').modal('hide');

                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesanpermission').html(html);
                }
            }
        })
        return false ;
    }
</script>

