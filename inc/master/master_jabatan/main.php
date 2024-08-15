<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Master Jabatan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <span class="green middle bolder" data-toggle="modal" data-target="#modal_add_master" onclick="add_dialog()">
                    <a href="#">
                        Add Jabatan
                    </a>
                </span>
            </div>
            <h1>
                Master Jabatan
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
                <h2 class="modal-title font-weight-bold">Add Jabatan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="add_jabatan()">
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
                <h2 class="modal-title font-weight-bold">Edit Jabatan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="edit_jabatan()">
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
            url: "inc/master/master_jabatan/view_report.php",
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
            url: "inc/master/master_jabatan/add_edit_delete.php",
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

    function add_jabatan() {
        var kode = $("#modal_add_master #kode").val();
        var nama_jabatan = $("#modal_add_master #nama_jabatan").val();
        if (kode == "" || nama_jabatan == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_jabatan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    kode : kode,
                    nama_jabatan: nama_jabatan
                },
                success: function(data) {
                    alert(data);
                    view_report();
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
            url: "inc/master/master_jabatan/add_edit_delete.php",
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

    function edit_jabatan() {
        var id = $("#modal_edit_master #id").val();
        var kode = $("#modal_edit_master #kode").val();
        var nama_jabatan = $("#modal_edit_master #nama_jabatan").val();
        if (kode == "" || nama_jabatan == "") {
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_jabatan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'edit_data',
                    id: id,
                    kode : kode,
                    nama_jabatan: nama_jabatan
                },
                success: function(data) {
                    alert(data);
                    view_report();
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
        		url: "inc/master/master_jabatan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'del',
                    id : id
                },
        		success:function(data){
                    alert(data);
                    view_report();
        		},
        		error:function(msg){
        			alert(msg);
        		}
            });	
        }
    }
</script>