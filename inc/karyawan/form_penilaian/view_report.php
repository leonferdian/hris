<?php
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();

$filter = "";
$filter .= isset($_GET['divisi']) && $_GET['divisi'] != "" ? " and divisi = '".$_GET['divisi']."'" : "";
$filter .= isset($_GET['tahun']) && $_GET['tahun'] != "" ? " and tahun = '".$_GET['tahun']."'" : "";
$group_by = " GROUP BY [kode_penilaian],[nama],[divisi],[tahun]";
$order_by = " ORDER BY [divisi] ASC";
?>
<script>
    $(document).ready(function(){
        var table = $('#data_master').DataTable({
            // order: [[ 0, "asc" ]],
            "paging": true,
            "bSort": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "bDestroy": true,
            "bProcessing": true,
            "bScrollCollapse" : true,
            "bRetrieve" : true,
            "responsive": true ,
            "deferRender": true,
            "scrollY": 550,
            "scrollX": true,
            "pageLength": 50,
            responsive: true,
            // fixedHeader: {
            //     header: true,
            //     footer: true
            // },
            // "footerCallback": function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
                
            //     // Remove the formatting to get integer data for summation
            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,.]/g, '')*1 :
            //             typeof i === 'number' ?
            //                 i : 0;
            //     };
            // }
        });	
    });
</script>
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th style="vertical-align:middle; text-align:center; width:100px;">No</th>
            <th style="vertical-align:middle; text-align:center;">Kode Penilaian</th>
            <th style="vertical-align:middle; text-align:center;">Judul</th>
            <th style="vertical-align:middle; text-align:center;">Divisi</th>
            <th style="vertical-align:middle; text-align:center;">Tahun</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Act</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT [kode_penilaian],[nama],[divisi],[tahun] FROM [db_hris].[dbo].[table_penilaian_karyawan] WHERE 1=1";
        $sql .= $filter;
        $sql .= $group_by;
        $sql .= $order_by;
        $no = 1;
        $stmt = $sqlsrv_hris->query($sql);
        if ($stmt):
        while ($row = $sqlsrv_hris->fetch_array($stmt)):
        ?>
            <tr class="odd gradeX">
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $no; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <a href="?sm=form_penilaian&act=detail&kode_penilaian=<?php echo $row['kode_penilaian']; ?>">
                        <?php echo $row['kode_penilaian']; ?>
                    </a>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['nama']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['divisi']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['tahun']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <a href="?sm=form_penilaian&act=edit_dialog&kode_penilaian=<?php echo $row['kode_penilaian']; ?>" class="btn btn-xs btn-info" >
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </a>
                    <a href="#" class="btn btn-xs btn-danger" onclick="del('<?php echo $row['kode_penilaian']; ?>')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>
                    </a>
                </td>
            </tr>
            <?php
                $no++;
            endwhile;
            else:
                echo $sql;
            endif;
            ?>
    </tbody>
</table>
<?php #echo $sql; ?>