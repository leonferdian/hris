<?php
include('../../../lib/fungsi_rupiah.php');
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
$url = "";
$tanggal = date("Y-m-d", strtotime($_GET['tanggal']));
$depo = "";
if (isset($_GET['depo']) && $_GET['depo'] != "") {
    if ($_GET['depo'] != "Blank") {
        $depo = " and absensi.depo = '" . $_GET['depo'] . "'";
    }
}
// $dayOfWeekNumber = date('N', strtotime($tanggal));
?>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
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

        MergeCommonRows($('.datatable'));
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
        var $table = $('.datatable');
        $table.floatThead({
            responsiveContainer: function($table) {
                return $table.closest('.datatable');
            },
            position: 'absolute'
        });
    }
</script>
<?php if ($_GET['act'] == "validasi"): ?>
<!-- <a href="#" onclick="export_excel('table_absensi_report', 'Absensi Karyawan-<?php echo date('Ymdhis'); ?>')"> <span class="vertical-date pull-right fa fa-file-excel-o text-success"> Export To Excel</span></a> -->
<h3 class="blue">Belum Validasi</h3>
<div class="table-responsive" id="list_core">
    <table class="table table-bordered table-hover datatable" id="table_absensi_report">
        <thead>
            <tr>
                <th class="center">#</th>
                <th class="center">Depo</th>
                <th class="center">Pin</th>
                <th class="center">Nik</th>
                <th class="center">Nama</th>
                <th class="center">Leave Category</th>
                <th class="center">Keterangan</th>
                <th class="center"><i class="fa fa-cog"></i></th>
            </tr>
        </thead>
        <tbody id="body_table_strata_segment">
            <?php
            $sql_mesin = "SELECT absensi.[depo]
                ,absensi.[pin]
                ,absensi.[nik]
                ,absensi.[nama]
                ,val.[keterangan]
                ,val.[lc_name]
            FROM [db_hris].[dbo].[table_absensi_log] absensi
            LEFT JOIN (SELECT
                a.*
                ,b.leave_category AS lc_name
                FROM [db_hris].[dbo].[table_absensi_validasi] a
                LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b
                ON a.leave_category = b.id
                WHERE a.date_absen = '" . $tanggal . "') val
                ON absensi.depo = val.depo
                AND absensi.pin = val.pin
                AND absensi.nik = val.nik
            WHERE absensi.date_absen = '" . $tanggal . "'
            ".$depo."
            and absensi.tanggal is null
            ORDER BY absensi.depo, absensi.nama, absensi.tanggal, absensi.time_scan_in, absensi.time_scan_out";
            // echo $sql_mesin;
            $no = 1;
            $stmt_mesin = $sqlsrv_hris->query($sql_mesin);
            if ($stmt_mesin):
            while ($row_mesin = $sqlsrv_hris->fetch_array($stmt_mesin)):
            ?>
                <tr>
                    <td class="center"><?php echo $no; ?></td>
                    <td class="center"><?php echo $_GET['depo']; ?></td>
                    <td class="center"><?php echo $row_mesin['pin']; ?></td>
                    <td class="center"><?php echo $row_mesin['nik']; ?></td>
                    <td class="center"><?php echo $row_mesin['nama']; ?></td>
                    <td class="center"><?php echo $row_mesin['lc_name']; ?></td>
                    <td class="center"><?php echo $row_mesin['keterangan']; ?></td>
                    <td class="center">
                        <?php if ($row_mesin['lc_name'] != ""): ?>
                            <i class="ace-icon fa fa-check bigger-120"></i> Validated
                        <?php else: ?>
                            <button class="btn btn-xs btn-white btn-info" onclick="validasi('<?php echo $row_mesin['pin']; ?>', '<?php echo $row_mesin['nik']; ?>', '<?php echo $tanggal; ?>', '<?php echo $row_mesin['nama']; ?>', '<?php echo $_GET['depo']; ?>')"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
                    </td>
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
<?php elseif ($_GET['act'] == "validasi_hrd"): ?>
<h3 class="blue">List Pengajuan Validasi</h3>
<div class="table-responsive" id="list_core">
    <table class="table table-bordered table-hover datatable" id="table_absensi_report">
        <thead>
            <tr>
                <th class="center">#</th>
                <th class="center">Depo</th>
                <th class="center">Pin</th>
                <th class="center">Nik</th>
                <th class="center">Nama</th>
                <th class="center">Leave Category</th>
                <th class="center">Keterangan</th>
                <th class="center">File SSD</th>
                <th class="center"><i class="fa fa-cog"></i></th>
            </tr>
        </thead>
        <tbody id="body_table_strata_segment">
            <?php
            $sql_validasi = "SELECT absensi.[depo]
                ,absensi.[pin]
                ,absensi.[nik]
                ,absensi.[nama]
                ,val.[date_absen]
                ,val.[lc_name] as leave_category
                ,val.[keterangan]
                ,val_hrd.[validasi_hrd]
                ,val_hrd.[nama_validator]
                ,val_hrd.[validate_date]
                ,ssd_file.[filename]
            FROM [db_hris].[dbo].[table_absensi_log] absensi
            LEFT JOIN (SELECT
                a.*
                ,b.leave_category AS lc_name
                FROM [db_hris].[dbo].[table_absensi_validasi] a
                LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b
                ON a.leave_category = b.id
                WHERE a.date_absen = '" . $tanggal . "') val
                ON absensi.depo = val.depo
                AND absensi.pin = val.pin
                AND absensi.nik = val.nik
			LEFT JOIN [db_hris].[dbo].[table_absensi_validasi_hrd] val_hrd
			ON absensi.depo = val_hrd.depo
			AND absensi.nik = val_hrd.nik
			AND absensi.pin = val_hrd.pin
            AND val.date_absen = val_hrd.date_absen
            LEFT JOIN [db_hris].[dbo].[table_file_ssd] ssd_file
            ON absensi.depo = ssd_file.depo
			AND absensi.nik = ssd_file.nik
            AND val.date_absen = ssd_file.tanggal
			WHERE absensi.tanggal is null
            and val.date_absen is not null
            and absensi.date_absen = '" . $tanggal . "'
			ORDER BY absensi.nama";
            $no2 = 1;
            $stmt_validasi = $sqlsrv_hris->query($sql_validasi);
            if ($stmt_validasi):
            while ($row_validasi = $sqlsrv_hris->fetch_array($stmt_validasi)):
                $hasil_validasi = "";
                if ($row_validasi['validasi_hrd'] == 1 && isset($row_validasi['validate_date'])) {
                    $hasil_validasi = "validated";
                } elseif ($row_validasi['validasi_hrd'] == 0 && isset($row_validasi['validate_date'])) {
                    $hasil_validasi = "rejected";
                }
            ?>
                <tr>
                    <td class="center"><?php echo $no2; ?></td>
                    <td class="center"><?php echo $row_validasi['depo']; ?></td>
                    <td class="center"><?php echo $row_validasi['pin']; ?></td>
                    <td class="center"><?php echo $row_validasi['nik']; ?></td>
                    <td class="center"><?php echo $row_validasi['nama']; ?></td>
                    <td class="center"><?php echo $row_validasi['leave_category']; ?></td>
                    <td class="center"><?php echo $row_validasi['keterangan']; ?></td>
                    <td class="center">
                        <?php if ($row_validasi['filename'] != ""): ?>
                        <a class="btn btn-white btn-xs btn-purple" onclick="view_iamge('<?php echo $row_validasi['filename']; ?>')"> <i class="ace-icon fa fa-picture-o file-image"></i> view</a>
                        <?php endif; ?>
                    </td>
                    <td style="vertical-align:middle; text-align:center; width:200px;">
                        <input type="hidden" id="depo_val" value="<?php echo $row_validasi['depo']; ?>">
                        <input type="hidden" id="pin_val" value="<?php echo $row_validasi['pin']; ?>">
                        <input type="hidden" id="nik_val" value="<?php echo $row_validasi['nik']; ?>">
                        <input type="hidden" id="tanggal_val" value="<?php echo date_format($row_validasi['date_absen'],"Y-m-d"); ?>">
                        <?php if (!isset($row_validasi['validate_date'])): ?>
                        <button class="btn btn-xs btn-success" onclick="validasi_hrd('valid')">
                            <i class="ace-icon fa fa-check bigger-120"></i> Validate
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="validasi_hrd('reject')">
                            <i class="ace-icon fa fa-times bigger-120"></i> Reject
                        </button>
                        <?php else: ?>
                            <?php echo $hasil_validasi; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                $no2++;
            endwhile;
            else:
                echo $sql_validasi;
            endif;
            ?>
        </tbody>
    </table>
</div>
<?php endif; ?>