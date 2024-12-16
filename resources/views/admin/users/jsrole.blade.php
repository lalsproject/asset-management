<script>

    function Role(id){
        TidakPilihRole();
        $('#paramhiddenrole').html("<input type='hidden' name='user_id' value = '" + id +"'>");
        $('#pesanrole').html("");
        $('#overlay-modal-role').hide();
        $('#btnSimpanDataRole').show();

        var v_url = '{{ route('admin.users.roles', ['user' => '-id-']) }}';
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

                    $('#modal-title-role').html(data.name);

                    var roles = data.roles;
                    $.each(roles, function(key, value) {
                        var res = value.replace(" ", "_");
                        $('#' + res).prop('checked', true);
                    });

                    $('#overlay-modal-role').hide();
                    $('#btnSimpanDataRole').show();

                    $('#ModalRole').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }


    function TidakPilihRole(){
        var role = document.getElementsByName("role[]");
        var jml=role.length;
        var b=0;
        for (b=0;b<jml;b++)
        {
            role[b].checked=false;
            
        }
    }

    function PilihSemuaRole(){
        var role = document.getElementsByName("role[]");
        var jml=role.length;
        var b=0;
        for (b=0;b<jml;b++)
        {
            role[b].checked=true;
            
        }
    }

    function SimpanRole(){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }


        $('#overlay-modal-role').show();
        $('#btnSimpanDataRole').hide();


        var datanya = $("#form_data_role").serialize();
        datanya = datanya + "&halaman=" + halaman_aktif;
        
        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }

        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.simpanroles') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal-role').hide();
                $('#btnSimpanDataRole').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  data.pesan;

                    html = html +  "</div>";
                    $('#pesan').html(html);
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);

                    $('#ModalRole').modal('hide');

                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesan').html(html);
                    $('#btnSimpanData').prop('disabled', false);
                }
            }
        })
        return false ;
    }
</script>

