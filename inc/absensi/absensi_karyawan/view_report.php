<?php
include('../../../lib/fungsi_rupiah.php');
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
// $_SESSION['id_company'] = $_GET['id_company'];
$url = "";
$tgl = date("Y-m-d", strtotime($_GET['tgl']));
$depo = "";
if (isset($_GET['depo']) && $_GET['depo'] != "") {
    if ($_GET['depo'] != "Blank") {
        $depo = " and depo = '" . $_GET['depo'] . "'";
    }
}
// $dayOfWeekNumber = date('N', strtotime($tgl));
?>
<script>
    $(document).ready(function() {
        $('#table_absensi_report').DataTable({
            // fixedHeader: {
            //     // header: true,
            //     footer: true
            // },
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

        MergeCommonRows($('#table_absensi_report'));
        // fix_thead();
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

    function fix_thead() {
        var $table = $('#table_absensi_report');
        $table.floatThead({
            responsiveContainer: function($table) {
                return $table.closest('.table-responsive');
            },
            position: 'absolute'
        });
    }
</script>
<div class="pull-right" >
<a href="#" onclick="export_excel('table_absensi_report', 'Absensi Karyawan-<?php echo date('Ymdhis'); ?>')"> <span class="btn btn-xs btn-white btn-success fa fa-file-excel-o"> Export To Excel</span></a>
&nbsp;
</div>
<i class="ace-icon fa fa-square text-danger" style="color:#ff0000;"></i> Tidak Masuk
<br><br>
<div class="table-responsive" id="list_core">
    <table class="table table-bordered table-hover" id="table_absensi_report">
        <thead>
            <tr>
                <th>Depo</th>
                <th>Pin</th>
                <th>Nik</th>
                <th>Nama</th>
                <th>Department</th>
                <th>Tanggal</th>
                <th>Shift OT</th>
                <th>Masuk</th>
                <th>Scan Masuk</th>
                <th>Terlambat</th>
                <th>Keluar</th>
                <th>Scan Keluar</th>
                <th>P.Cepat</th>
                <th>Nilai</th>
                <th>Durasi</th>
                <th>Keterangan</th>
                <th>verifikasi_apps</th>
                <th>leave_category</th>
            </tr>
        </thead>
        <tbody id="body_table_strata_segment">
            <?php
            $sql_mesin = "SELECT [depo]
                ,[pin]
                ,[nik]
                ,[nama]
                ,[func_name]
                ,[tanggal]
                ,[kode]
                ,[shift_ot]
                ,[shift]
                ,[shift_name]
                ,[jam_masuk]
                ,[jam_keluar]
                ,[start_scan_masuk]
                ,[start_scan_keluar]
                ,[end_scan_masuk]
                ,[end_scan_keluar]
                ,[time_scan_in]
                ,[time_scan_out]
                ,[keterangan]
                ,[nilai]
                ,[verifikasi_apps]
                ,[leave_category]
                ,[telat]
                ,[p_cepat]
                ,[durasi]
                ,[date_absen]
            FROM [db_hris].[dbo].[table_absensi_log]
            WHERE [date_absen] = '".$tgl."' ".$depo."
            ORDER BY depo, nama, tanggal, time_scan_in, time_scan_out";
            $no = 1;
            $stmt_mesin = $sqlsrv_hris->query($sql_mesin);
            if ($stmt_mesin):
            while ($row_mesin = $sqlsrv_hris->fetch_array($stmt_mesin)):
            ?>
                <tr style="<?php echo isset($row_mesin['tanggal']) ? "" : "background-color: red;"; ?>">
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['depo']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['pin']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['nik']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['nama']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['func_name']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['tanggal']) ? date_format($row_mesin['tanggal'], "d-m-Y") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['shift_ot']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['jam_masuk']) ? date_format($row_mesin['jam_masuk'], "H:i:s") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['time_scan_in']) ? date_format($row_mesin['time_scan_in'], "H:i:s") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['telat']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['jam_keluar']) ? date_format($row_mesin['jam_keluar'], "H:i:s") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['time_scan_out']) ? date_format($row_mesin['time_scan_out'], "H:i:s") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['p_cepat']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['tanggal']) ? 1 : $row_mesin['nilai']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['durasi']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['keterangan']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['tanggal']) ? 1 : $row_mesin['verifikasi_apps']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['tanggal']) ? "masuk" : $row_mesin['leave_category']; ?></td>
                </tr>
            <?php
                $no++;
            endwhile;
            else:
                echo $sql_mesin;
            endif;
            ?>
        </tbody>
    </table>
</div>