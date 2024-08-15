<?php
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
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
            "autoWidth": false,
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
            <th style="vertical-align:middle; text-align:center; width:100px;">timestamp</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">score</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">nama lengkap</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">nomor hp</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">nama jabatan dilamar</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Act</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT a.*, b.total_jawaban_benar FROM [db_hris].[dbo].[table_biodata_pelamar] a
                LEFT JOIN [db_hris].[dbo].[table_tes_hasil] b
                ON a.id = b.id_peserta
                order by a.[nama]";
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
                    <?php echo date_format($row['date_create'], "Y-m-h H:i:s"); ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo format_rupiah($row['total_jawaban_benar'])."/40"; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['nama']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['no_hp']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['kode_jabatan']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <button class="btn btn-xs btn-info" onclick="edit_dialog('<?php echo $row['id']; ?>')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="del('<?php echo $row['id']; ?>',)">
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
<?php #echo $sql; ?>