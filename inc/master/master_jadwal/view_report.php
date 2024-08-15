<?php
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
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
            <!-- <th style="vertical-align:middle; text-align:center;">Depo</th> -->
            <th style="vertical-align:middle; text-align:center;">Kode Shift</th>
            <th style="vertical-align:middle; text-align:center;">Nama Shift</th>
            <th style="vertical-align:middle; text-align:center;">Jam Masuk</th>
            <th style="vertical-align:middle; text-align:center;">Jam Keluar</th>
            <th style="vertical-align:middle; text-align:center;">Start Scan Masuk</th>
            <th style="vertical-align:middle; text-align:center;">Start Scan Keluar</th>
            <th style="vertical-align:middle; text-align:center;">End Scan Masuk</th>
            <th style="vertical-align:middle; text-align:center;">End Scan Keluar</th>
            <th style="vertical-align:middle; text-align:center;">Vendor</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Act</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "select * from [db_hris].[dbo].[table_absensi_mesin_jadwal]";
        $no = 1;
        $stmt = $sqlsrv_hris->query($sql);
        if ($stmt):
        while ($row = $sqlsrv_hris->fetch_array($stmt)):
        ?>
            <tr class="odd gradeX">
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $no; ?>
                </td>
                <!-- <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['depo']; ?>
                </td> -->
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['shift']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['shift_name']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['jam_masuk']) ? date_format($row['jam_masuk'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['jam_keluar']) ? date_format($row['jam_keluar'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['start_scan_masuk']) ? date_format($row['start_scan_masuk'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['start_scan_keluar']) ? date_format($row['start_scan_keluar'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['end_scan_masuk']) ? date_format($row['end_scan_masuk'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['end_scan_keluar']) ? date_format($row['end_scan_keluar'], "H:i") : ""; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['vendor']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <button class="btn btn-xs btn-info" onclick="edit_dialog('<?php echo $row['id']; ?>')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="del('<?php echo $row['id']; ?>','<?php echo $row['depo']; ?>')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>
                    </button>
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