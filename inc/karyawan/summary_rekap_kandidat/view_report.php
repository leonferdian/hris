<?php
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();

$filter = "";
// $filter .= isset($_GET['divisi']) && $_GET['divisi'] != "" ? " and divisi = '" . $_GET['divisi'] . "'" : "";
// $filter .= isset($_GET['tahun']) && $_GET['tahun'] != "" ? " and tahun = '" . $_GET['tahun'] . "'" : "";
$filter .= isset($_GET['slc_jabatan']) && $_GET['slc_jabatan'] != "" ? " and a.[kode_jabatan] = '" . $_GET['slc_jabatan'] . "'" : "";
$group_by = " GROUP BY a.[kode_jabatan]";
$order_by = " ORDER BY a.[kode_jabatan]";
?>
<script>
    $(document).ready(function () {
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
            "bScrollCollapse": true,
            "bRetrieve": true,
            "responsive": true,
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
<div class="row">
    <?php
    $sql = "SELECT
                    a.[kode_jabatan]
                    ,COUNT(a.[id]) AS total_kandidat
                    ,COUNT(CASE WHEN b.[pertimbangkan] = 'N' OR b.[pertimbangkan] IS NULL THEN a.[id] END) as tidak_memenuhi_syarat
                    ,COUNT(CASE WHEN b.[pertimbangkan] = 'Y' THEN a.[id] END) as dipertimbangkan
                    ,COUNT(CASE WHEN b.[final] = 'Y' THEN a.[id] END) as diterima
                FROM [db_hris].[dbo].[table_biodata_pelamar] a
                LEFT JOIN [db_hris].[dbo].[table_rekap_kandidat] b
                ON a.id = b.id_pelamar
                WHERE 1=1";
    $sql .= $filter;
    $sql .= $group_by;
    $sql .= $order_by;
    $stmt = $sqlsrv_hris->query($sql);
    if ($stmt):
        while ($row = $sqlsrv_hris->fetch_array($stmt)):
            ?>
            <div class="col-xs-12 col-sm-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title"><?php echo $row['kode_jabatan']; ?></h4>

                        <!-- <div class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>

                            <a href="#" data-action="close">
                                <i class="ace-icon fa fa-times"></i>
                            </a>
                        </div> -->
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr class="odd gradeX">
                                        <th style="vertical-align:middle;">Total Kandidat</th>
                                        <td style="vertical-align:middle; text-align:center;">
                                            <?php echo $row['total_kandidat']; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd gradeX">
                                        <th style="vertical-align:middle;">Tidak Memenuhi Syarat</th>
                                        <td style="vertical-align:middle; text-align:center;">
                                            <?php echo $row['tidak_memenuhi_syarat']; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd gradeX">
                                        <th style="vertical-align:middle;">Dipertimbangkan</th>
                                        <td style="vertical-align:middle; text-align:center;">
                                            <?php echo $row['dipertimbangkan']; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd gradeX">
                                        <th style="vertical-align:middle;">Diterima</th>
                                        <td style="vertical-align:middle; text-align:center;">
                                            <?php echo $row['diterima']; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.span -->
            <?php
        endwhile;
    else:
        echo $sql;
    endif;
    ?>
</div>
<?php #echo $sql; ?>