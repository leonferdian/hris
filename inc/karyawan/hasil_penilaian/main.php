<?php if (isset($_GET['act']) && $_GET['act'] == "add_dialog"):  ?>
    <?php include 'inc/karyawan/hasil_penilaian/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "edit_dialog"):  ?>
    <?php include 'inc/karyawan/hasil_penilaian/form_data.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "detail"):  ?>
    <?php include 'inc/karyawan/hasil_penilaian/form_data.php'; ?>
<?php else: ?>
<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Laporan Kinerja Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right hide">
                <a href="?sm=hasil_penilaian&act=add_dialog" class="btn btn-xs btn-white btn-primary">
                    <i class="fa fa-plus"></i> Create New
                </a>
            </div>
            <h1>
                Laporan Kinerja Karyawan
            </h1>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Filter</h4>
                        <span class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="form-inline">
                                <span id="span-divisi">
                                    <label for="slc_divisi">Divisi: </label>
                                    <select class="form-control input-sm select2" name="slc_divisi" id="slc_divisi" data-placeholder="Select Divisi" onchange="">
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT * FROM [db_hris].[dbo].[table_master_divisi] ORDER BY [nama_divisi]";
                                        $stmt = $sqlsrv_hris->query($sql);
                                        while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                            echo '<option value="' . $row['nama_divisi'] . '">' . strtoupper($row['nama_divisi']) . '</option>';
                                        endwhile;
                                        ?>
                                    </select>
                                </span>
                                <span id="span-tahun">
                                    <label for="slc_tahun">Tahun: </label>
                                    <select class="form-control input-sm select2" name="slc_tahun" id="slc_tahun" data-placeholder="Select Tahun" onchange="">
                                        <option value=""></option>
                                        <?php
                                        $sql_user = "SELECT DISTINCT [tahun] FROM [db_hris].[dbo].[table_penilaian_karyawan] order by [tahun]";
                                        $stmt_user = $sqlsrv_hris->query($sql_user);
                                        while ($row_user = $sqlsrv_hris->fetch_array($stmt_user)) {
                                            ?>
                                            <option value="<?php echo $row_user['tahun']; ?>" <?php echo $row_user['tahun'] == date("Y") ? "selected" : ""; ?>>
                                                <?php echo $row_user['tahun']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </span>
                                <span class="pull-right">
                                    <button type="button" class="btn btn-info btn-sm" id="view_entity2" onclick="view_report()">
                                        <i class="ace-icon fa fa-search bigger-110"></i>View
                                    </button>
                                </span>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<script>
    $(document).ready(function() {
        $('.select2').css('width', '220px').select2({ allowClear: true })
            .on('change', function () {
                // $(this).closest('form').validate().element($(this));
            });

        view_report();
    });

    function view_report() {
        $('#progress').show();
        var divisi = $('#slc_divisi').val();
        var tahun = $('#slc_tahun').val();
        $.ajax({
            type: "GET",
            url: "inc/karyawan/hasil_penilaian/view_report.php",
            traditional: true,
            data: {
                divisi: divisi,
                tahun: tahun,
            },
            success: function(data) {
                $("#list_vol").html(data);
                $('#progress').hide();
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function del(id){
        if (window.confirm("Apakah anda yakin ingin menghapus data ini?")) {
            $.ajax({
        		type:"POST",
        		url: "inc/karyawan/hasil_penilaian/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'del',
                    kode_soal : id
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