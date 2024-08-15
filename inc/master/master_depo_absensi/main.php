<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Hak Akses Kepala Depo</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <span class="green middle bolder" data-toggle="modal" data-target="#modal_add_master" onclick="add_dialog()">
                    <a href="#">
                        Tambah Hak Akses
                    </a>
                </span>
            </div>
            <h1>
                Hak Akses Kepala Depo
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                    <div id="list_vol">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_add_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Master Kepala Depo</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="add_lc()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Master Kepala Depo</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="edit_lc()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        view_report();
    });

    function view_report() {
        $('#progress').show();
        $.ajax({
            type: "GET",
            url: "inc/master/master_depo_absensi/view_report.php",
            traditional: true,
            // data: },
            success: function(data) {
                $("#list_vol").html(data);
                $('#progress').hide();
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function add_dialog() {
        $('#modal_add_master').modal('hide');
        $.ajax({
            type: "POST",
            url: "inc/master/master_depo_absensi/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'add_dialog'
            },
            success: function(data) {
                $("#modal_add_master .modal-body").html(data);
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function add_lc() {
        var email = $("#modal_add_master #email").val();
        var kode_depo = $("#modal_add_master #slc_depo").val();
        if (email == "" || kode_depo == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_depo_absensi/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    email : email,
                    kode_depo: kode_depo
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Ditambahkan") {
                        $('#modal_add_master').modal('hide');
                        view_report();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function edit_dialog(id) {
        $('#modal_edit_master').modal('show');
        $.ajax({
            type:"POST",
            url: "inc/master/master_depo_absensi/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'edit_dialog',
                id : id
            },
            success:function(data){											
                $("#modal_edit_master .modal-body").html(data);
            },
            error:function(msg){
                alert(msg);
            }
        });
    }

    function edit_lc() {
        var id = $("#modal_edit_master #id").val();
        var email = $("#modal_edit_master #email").val();
        var kode_depo = $("#modal_edit_master #slc_depo").val();
        if (email == "" || kode_depo == "") {
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_depo_absensi/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'edit_data',
                    id: id,
                    email : email,
                    kode_depo: kode_depo
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Diubah") {
                        $('#modal_edit_master').modal('hide');
                        view_report();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function del(id){
        if (window.confirm("Apakah anda yakin ingin menghapus data ini?")) {
            $.ajax({
        		type:"POST",
        		url: "inc/master/master_depo_absensi/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'del',
                    id : id
                },
        		success:function(data){
                    alert(data);
                    if (data.trim() == "Data Berhasil dihapus") {
                        view_report();
                    }
        		},
        		error:function(msg){
        			alert(msg);
        		}
            });	
        }
    }
</script>