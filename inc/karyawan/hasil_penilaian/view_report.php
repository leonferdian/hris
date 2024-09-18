<?php
include('../../../lib/database.php');
include('../../../lib/fungsi_rupiah.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();

$filter = "";
$filter .= isset($_GET['divisi']) && $_GET['divisi'] != "" ? " and a.divisi = '".$_GET['divisi']."'" : "";
$filter .= isset($_GET['tahun']) && $_GET['tahun'] != "" ? " and a.tahun = '".$_GET['tahun']."'" : "";
$filter .= " and b.[nama_karyawan] is not null";
$group_by = " GROUP BY a.[kode_penilaian]
                ,a.[nama]
                ,a.[divisi]
                ,a.[tahun]
                ,b.[nama_karyawan]
                ,b.[nik]
				,b.[hasil]
				,b.[arsip]
                ,c.[jabatan]
                ,c.[lama_kerja_th]
                ,c.[lama_kerja_bln]";
$order_by = " ORDER BY a.[divisi],b.[nama_karyawan],a.[tahun] ASC";
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
<div class="pull-right">
    <a type="button" class="btn btn-purple btn-sm" href="?sm=hasil_penilaian&act=report_team&divisi=<?php echo $_GET['divisi']; ?>">
        <i class="ace-icon fa fa-book bigger-110"></i>Report Team
    </a>
</div>
<br><br>
<table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
    <thead>
        <tr>
            <th style="vertical-align:middle; text-align:center; width:100px;">No</th>
            <th style="vertical-align:middle; text-align:center;">Kode Penilaian</th>
            <th style="vertical-align:middle; text-align:center;">Judul</th>
            <th style="vertical-align:middle; text-align:center;">Divisi</th>
            <th style="vertical-align:middle; text-align:center;">Tahun</th>
            <th style="vertical-align:middle; text-align:center;">Nama</th>
            <th style="vertical-align:middle; text-align:center;">NIK</th>
            <th style="vertical-align:middle; text-align:center;">Jabatan</th>
            <th style="vertical-align:middle; text-align:center;">Masa Kerja</th>
            <th style="vertical-align:middle; text-align:center;">Total Score dr Karyawan</th>
            <th style="vertical-align:middle; text-align:center;">Total Score dr Atasan</th>
            <th style="vertical-align:middle; text-align:center;">Validasi Atasan</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Act</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT 
		        SUM(b.[score]) as total_score
		        ,SUM(b.[score_atasan]) as total_score_atasan
                ,a.[kode_penilaian]
                ,a.[nama]
                ,a.[divisi]
                ,a.[tahun]
                ,b.[nama_karyawan]
                ,b.[nik]
				,b.[hasil]
				,b.[arsip]
                ,c.[jabatan]
                ,c.[lama_kerja_th]
                ,c.[lama_kerja_bln]
                FROM [db_hris].[dbo].[table_penilaian_karyawan] a
                LEFT JOIN [db_hris].[dbo].[table_penilaian_karyawan_result] b
                    ON b.[id_penilaian] = a.id
                LEFT JOIN [db_hris].[dbo].[table_karyawan] c
                    ON b.nik = c.nik
                WHERE 1=1";
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
                    <a href="?sm=hasil_penilaian&act=detail<?php echo "&kode_penilaian=".$row['kode_penilaian']."&nik=".$row['nik']; ?>">
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
                    <?php echo $row['nama_karyawan']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['nik']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['jabatan']; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo $row['lama_kerja_th'] . " tahun " . $row['lama_kerja_bln'] . " bulan"; ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo format_rupiah($row['total_score']); ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo format_rupiah($row['total_score_atasan']); ?>
                </td>
                <td style="vertical-align:middle; text-align:center;">
                    <?php echo isset($row['hasil']) && $row['hasil'] == 'complete' ? '<i class="ace-icon fa fa-check-circle bigger-120 blue"> Verified</i>' : '<i class="ace-icon fa fa-clock-o bigger-120 blue"> In Review</i>'; ?>
                </td>
                <td style="vertical-align:middle; text-align:center; width: 180px;">
                    <?php if (isset($row['arsip']) && $row['arsip'] == '1'): ?>
                        <i class="ace-icon fa fa-check bigger-120 text-success"> Diarsipkan</i>
                    <?php else: ?>
                        <?php if ($row['hasil'] == 'complete'): ?>
                        <a href="?sm=hasil_penilaian&act=edit_dialog<?php echo "&kode_penilaian=".$row['kode_penilaian']."&nik=".$row['nik']; ?>" class="btn btn-xs btn-warning" >
                            <i class="ace-icon fa fa-folder-open bigger-120"> Arsip</i>
                        </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="?sm=hasil_penilaian&act=report<?php echo "&kode_penilaian=".$row['kode_penilaian']."&nik=".$row['nik']; ?>" class="btn btn-xs btn-info" >
                        <i class="ace-icon fa fa-book bigger-120"> Report</i>
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