<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris'); 
if(isset($_GET['detail_page']) && $_GET['detail_page'] == "form_karyawan"):
	include "inc/karyawan/data_karyawan/form_karyawan.php";
else:
?>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="?page=dashboard">Home</a>
                </li>
                <li class="active">Data Karyawan</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <div class="pull-right">
                    <a class="btn btn-xs btn-success" href="?mm=data_karyawan&detail_page=form_karyawan&act=add_karyawan" title="Create Karyawan">
                        <i class="ace-icon fa fa-plus bigger-120"></i> Tambah Data
                    </a>
                </div>
                <h1>
                    Data Karyawan
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
                                        <th>nama_pt</th>
                                        <th>entity</th>
                                        <th>depo</th>
                                        <th>Nama</th>
                                        <th>pin</th>
                                        <th>nik</th>
                                        <th>kategori_karyawan</th>
                                        <th>nama_outsourcing</th>
                                        <th>status_kontrak_kerja</th>
                                        <th>tgl_akhir_pkwt</th>
                                        <th class="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql ="select * from [db_hris].[dbo].[table_karyawan] order by nama_pt,entity,depo,nama asc";
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
                                        <td><?php echo $row['nik'];?></td>
                                        <td><?php echo $row['kategori_karyawan'];?></td>
                                        <td><?php echo $row['nama_outsourcing'];?></td>
                                        <td><?php echo $row['status_kontrak_kerja'];?></td>
                                        <td><?php echo $row['tgl_akhir_pkwt'] != "" ? date_format($row['tgl_akhir_pkwt'],"Y-m-d") : "";?></td>
                                        <td class="center">
                                            <div class="btn-group">
                                                <a class="btn btn-xs btn-white btn-info" href="?mm=data_karyawan&detail_page=form_karyawan&act=edit_karyawan&id=<?php echo $row['id'];?>">
                                                    Edit
                                                </a>
                                                <a class="btn btn-xs btn-white btn-warning" href="?mm=data_karyawan&detail_page=form_karyawan&act=detail_karyawan&id=<?php echo $row['id'];?>">
                                                    Detail
                                                </a>
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