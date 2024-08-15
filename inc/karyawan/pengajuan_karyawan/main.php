<?php if (isset($_GET['act']) && $_GET['act'] == "add_dialog"):  ?>
    <?php include 'inc/karyawan/pengajuan_karyawan/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "edit_dialog"):  ?>
    <?php include 'inc/karyawan/pengajuan_karyawan/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "detail"):  ?>
    <?php include 'inc/karyawan/pengajuan_karyawan/form_data.php'; ?>
<?php else: ?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Pengajuan Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <!-- <span class="green middle bolder" data-toggle="modal" data-target="#modal_add_master" onclick="add_dialog()"> -->
                    <a href="?sm=pengajuan_karyawan&act=add_dialog">
                        Tambah Pengajuan
                    </a>
                <!-- </span> -->
            </div>
            <h1>
                Pengajuan Karyawan
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
                <h2 class="modal-title font-weight-bold">Form Pengajuan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="save()">
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
                <h2 class="modal-title font-weight-bold">Form Pengajuan</h2>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="edit()">
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
            url: "inc/karyawan/pengajuan_karyawan/view_report.php",
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
            url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
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

    function save() {
        var email_kadiv = $("#modal_add_master #email_kadiv").val();
        var kode_divisi = $("#modal_add_master #kode_divisi").val();
        var kode_jabatan_kandidat = $("#modal_add_master #kode_jabatan_kandidat").val();
        // var kriteria = $("#modal_edit_master #kriteria").val();
        var kriteria = $("#modal_edit_master #editor2").html();
        var jenis_kelamin = $("#modal_edit_master #jenis_kelamin").val();
        var tgl_pengajuan = $("#modal_add_master #tgl_pengajuan").val();
        var tgl_deadline = $("#modal_add_master #tgl_deadline").val();
        
        if (email_kadiv == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    email_kadiv : email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
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
            url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
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

    function edit() {
        var id = $("#modal_edit_master #id").val();
        var email_kadiv = $("#modal_edit_master #email_kadiv").val();
        var kode_divisi = $("#modal_edit_master #kode_divisi").val();
        var kode_jabatan_kandidat = $("#modal_edit_master #kode_jabatan_kandidat").val();
        // var kriteria = $("#modal_edit_master #kriteria").val();
        var kriteria = $("#modal_edit_master #editor2").html();
        var jenis_kelamin = $("#modal_edit_master #jenis_kelamin").val();
        var tgl_pengajuan = $("#modal_edit_master #tgl_pengajuan").val();
        var tgl_deadline = $("#modal_edit_master #tgl_deadline").val();

        if (email_kadiv == "") {
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'edit_data',
                    id: id,
                    email_kadiv : email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
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
        		url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
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
<?php endif; ?>