<?php if (isset($_GET['act']) && $_GET['act'] == "add_dialog"):  ?>
    <?php include 'inc/karyawan/permintaan_karyawan/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "edit_dialog"):  ?>
    <?php include 'inc/karyawan/permintaan_karyawan/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "detail"):  ?>
    <?php include 'inc/karyawan/permintaan_karyawan/form_data.php'; ?>
<?php else: ?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Permintaan Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Permintaan Karyawan
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
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Form Pengajuan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Close
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
            url: "inc/karyawan/permintaan_karyawan/view_report.php",
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

    function update_status(id, status) {
        $.ajax({
            type: "POST",
            url: "inc/karyawan/permintaan_karyawan/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'update_status',
                id : id,
                status: status
            },
            success: function(data) {
                alert(data);
                if (data.trim() == "Data berhasil diupdate") {
                    alert(data);
                    view_report();
                }
            },
                error: function(msg) {
                alert(msg);
            }
        });
    }

    function detail(id) {
        $('#modal_detail').modal('show');
        $.ajax({
            type:"POST",
            url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
            traditional: true,
            data:{
                act: 'detail',
                id : id
            },
            success:function(data){											
                $("#modal_detail .modal-body").html(data);
            },
            error:function(msg){
                alert(msg);
            }
        });
    }
</script>
<?php endif; ?>