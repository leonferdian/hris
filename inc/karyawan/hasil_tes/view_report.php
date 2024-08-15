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
            <th style="vertical-align:middle; text-align:center;">Nama Peserta</th>
            <th style="vertical-align:middle; text-align:center;">Total Soal</th>
            <th style="vertical-align:middle; text-align:center;">Total Jawaban Benar</th>
            <th style="vertical-align:middle; text-align:center;">Total Score</th>
            <th style="vertical-align:middle; text-align:center;">Tanggal Tes</th>
            <!-- <th style="vertical-align:middle; text-align:center; width:100px;">Act</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT a.*, b.[nama] FROM [db_hris].[dbo].[table_tes_hasil] a
                LEFT JOIN [db_hris].[dbo].[table_biodata_pelamar] b
                ON a.[id_peserta] = b.id
                ORDER BY [date_create] DESC";
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
                    <a href="?sm=hasil_tes&act=detail&id_peserta=<?php echo $row['id_peserta']; ?>&nama=<?php echo $row['nama']; ?>">
                        <?php echo $row['nama']; ?>
                    </a>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['total_soal']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['total_jawaban_benar']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['total_score']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo date_format($row['date_create'],"Y-m-d H:i:s"); ?>
                </td>
                <!-- <td style="vertical-align:middle; text-align:center;">
                    <a href="?sm=hasil_tes&act=edit_dialog&kode_soal=<?php echo $row['kode_soal']; ?>" class="btn btn-xs btn-info" >
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </a>
                    <a href="#" class="btn btn-xs btn-danger" onclick="del('<?php echo $row['kode_soal']; ?>')">
                        <i class="ace-icon fa fa-trash bigger-120"></i>
                    </a>
                </td> -->
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