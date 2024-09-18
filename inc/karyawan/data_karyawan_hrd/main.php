<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris'); 
if(isset($_GET['detail_page']) && $_GET['detail_page'] == "form_karyawan"):
	include "inc/karyawan/data_karyawan_hrd/form_karyawan.php";
else:
?>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="?page=dashboard">Home</a>
                </li>
                <li class="active">Verifikasi Perubahan Data Karyawan</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <div class="pull-right hide">
                    <a class="btn btn-xs btn-success" href="?sm=data_karyawan_hrd&detail_page=form_karyawan&act=add_karyawan" title="Create Karyawan">
                        <i class="ace-icon fa fa-plus bigger-120"></i> Tambah Data
                    </a>
                </div>
                <h1>
                    Verifikasi Perubahan Data Karyawan
                </h1>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama PT</th>
                                        <th>Entity</th>
                                        <th>Depo</th>
                                        <th>Nama</th>
                                        <th>PIN</th>
                                        <th>NIK</th>
                                        <th>Kategori Karyawan</th>
                                        <th>Nama Outsourcing</th>
                                        <th>Status Kontrak Kerja</th>
                                        <th>Tgl Akhir PKWT</th>
                                        <th>Status Pengajuan</th>
                                        <th>Last Update</th>
                                        <th class="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql ="select * from [db_hris].[dbo].[table_karyawan_draft] order by nama_pt,entity,depo,nama asc";
                                        $stmt = $sqlsrv_hris->query($sql);  
                                        $no=1;
                                        while($row = $sqlsrv_hris->fetch_array($stmt)):
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $no;?>.</td>
                                        <td><?php echo $row['nama_pt'];?></td>
                                        <td><?php echo $row['entity'];?></td>
                                        <td><?php echo $row['depo'];?></td>
                                        <td><?php echo $row['nama'];?></td>
                                        <td><?php echo $row['pin'];?></td>
                                        <td>
                                            <a href="?sm=data_karyawan_hrd&detail_page=form_karyawan&act=detail_karyawan&id=<?php echo $row['id'];?>">
                                                <?php echo $row['nik'];?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['kategori_karyawan'];?></td>
                                        <td><?php echo $row['nama_outsourcing'];?></td>
                                        <td><?php echo $row['status_kontrak_kerja'];?></td>
                                        <td><?php echo $row['tgl_akhir_pkwt'] != "" ? date_format($row['tgl_akhir_pkwt'],"Y-m-d") : "";?></td>
                                        <td>
                                        <?php if ($row['status_data'] == "complete"): ?>
                                            <i class="text-success"><?php echo $row['status_data'];?></i>
                                        <?php elseif ($row['status_data'] == "proses"): ?>
                                            <i class="text-warning"><?php echo $row['status_data'];?></i>
                                        <?php else: ?>
                                            <i class="text-danger"><?php echo $row['status_data'];?></i>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['tgl_update'] != "" ? date_format($row['tgl_update'],"Y-m-d H:i:s") : "";?></td>
                                        <td class="center" style="width: 100px;">
                                            <div class="btn-group">
                                                <?php if ($row['status_data'] != "complete"): ?>
                                                <a class="btn btn-xs btn-white btn-info" href="?sm=data_karyawan_hrd&detail_page=form_karyawan&act=edit_karyawan&id=<?php echo $row['id'];?>">
                                                <i class="fa fa-check-circle"></i> Verifikasi
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                   <?php
                                        $no++;
                                        endwhile;
                                   ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
        //alert('ss');
        $('#dataTables-example').DataTable({
            responsive: true,
            "paging": false,
            "bSort": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "bDestroy": true,
            "bProcessing": true,
            "bScrollCollapse": true,
            "bRetrieve": true,
            "responsive": true,
            "deferRender": true,
            "scrollY": 550,
            "scrollX": true,
            "pageLength": 50
        });
        MergeCommonRows($('#dataTables-example'));
    });

    function MergeCommonRows(table) {
        //alert(table)
        var firstColumnBrakes = [];
        // iterate through the columns instead of passing each column as function parameter:
        for (var i = 1; i <= table.find('th').length; i++) {
            var previous = null,
                cellToExtend = null,
                rowspan = 1;
            //table.find("td:nth-child(" + i + ")").each(function(index, e){
            table.find(".td_l1:nth-child(" + i + ")").each(function(index, e) {
                var jthis = $(this),
                    content = jthis.text();
                //alert(content);
                // check if current row "break" exist in the array. If not, then extend rowspan:
                if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1 && typeof content === "string") {
                    // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                    jthis.addClass('hidden');
                    cellToExtend.attr("rowspan", (rowspan = rowspan + 1));
                } else {
                    // store row breaks only for the first column:
                    if (i === 1) firstColumnBrakes.push(index);
                    rowspan = 1;
                    previous = content;
                    cellToExtend = jthis;
                }
            });
        }
        // now remove hidden td's (or leave them hidden if you wish):
        $('td.hidden').remove();
    }
</script>
	
<?php endif; ?>