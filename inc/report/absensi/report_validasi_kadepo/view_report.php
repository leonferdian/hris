<?php
include('../../../../lib/fungsi_rupiah.php');
include('../../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
// Get the month and year from the request (e.g., from a form submission)
$month = isset($_GET['month']) ? $_GET['month'] : date('m'); // default to current month
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');     // default to current year

// Determine the number of days in the selected month
$numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Build dynamic SQL for each day of the month
$columns = [];
for ($day = 1; $day <= $numDaysInMonth; $day++) {
    $dayFormatted = str_pad($day, 2, '0', STR_PAD_LEFT); // format day as '01', '02', ..., '31'
    $columns[] = "COUNT(CASE WHEN DATEPART(day, date_absen) = '$day' AND lc_name IS NOT NULL THEN pin END) AS validated_tgl_$dayFormatted";
    $columns[] = "COUNT(CASE WHEN DATEPART(day, date_absen) = '$day' AND lc_name IS NULL THEN pin END) AS not_validated_tgl_$dayFormatted";
    $columns[] = "COUNT(CASE WHEN DATEPART(day, date_absen) = '$day' THEN pin END) AS total_tgl_$dayFormatted";
}

// Join the columns into a single string
$columnsSql = implode(",\n  ", $columns);

// SQL query
$sql = "SELECT 
        depo,
        $columnsSql
        FROM (
            SELECT
                absensi.[depo]
                ,absensi.[pin]
                ,absensi.[nik]
                ,absensi.[nama]
                ,absensi.date_absen
                ,kadep.username
                ,val.[keterangan]
                ,val.[lc_name]
            FROM [db_hris].[dbo].[table_absensi_log] absensi
            LEFT JOIN [db_hris].[dbo].[table_depo_absensi] kadep
            ON absensi.depo = kadep.kode_depo
            LEFT JOIN (SELECT
                a.*
            ,b.leave_category AS lc_name
            FROM [db_hris].[dbo].[table_absensi_validasi] a
            LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b
                ON a.leave_category = b.id) val
            ON absensi.depo = val.depo
                AND absensi.pin = val.pin
                AND absensi.nik = val.nik
                AND absensi.date_absen = val.date_absen
            WHERE kadep.username = '".$_SESSION['username']."'
            AND absensi.tanggal IS NULL
                AND DATEPART(month, absensi.date_absen) = '" . $month . "'
                AND DATEPART(year, absensi.date_absen) = '" . $year . "'
        ) validasi
        GROUP BY depo
        ORDER BY depo
        ";
$stmt_mesin = $sqlsrv_hris->query($sql);
// $dayOfWeekNumber = date('N', strtotime($tanggal));
?>
<script>
    $(document).ready(function () {
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
            table.find(".td_l1:nth-child(" + i + ")").each(function (index, e) {
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
            responsiveContainer: function ($table) {
                return $table.closest('.datatable');
            },
            position: 'absolute'
        });
    }
</script>
<div class="table-header">
    <div class="pull-right">
        <a class="btn btn-white btn-xs btn-success"
            onclick="export_excel('table_absensi_report', 'Report Validasi Kepala Depo-<?php echo date('Ymdhis'); ?>')"><i
            class="fa fa-file-excel-o text-success"></i> Export To Excel</a>
        &nbsp;
    </div>
</div>
<br><br>
<div class="table-responsive">
    <table class="table table-bordered table-hover datatable" id="table_absensi_report">
        <thead>
            <tr>
                <th class="center" rowspan="2">#</th>
                <th class="center" style="border-right: #000 1px solid;" rowspan="2">Depo</th>
                <?php
                for ($day = 1; $day <= $numDaysInMonth; $day++) {
                    $dayFormatted = str_pad($day, 2, '0', STR_PAD_LEFT);
                    echo '<th class="center" style="border-right: #000 1px solid;" colspan="3">' . $dayFormatted . '</th>';
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($day = 1; $day <= $numDaysInMonth; $day++) {
                    echo '<th class="center">Validated</th>';
                    echo '<th class="center">Not Validated</th>';
                    echo '<th class="center" style="border-right: #000 1px solid;">Total</th>';
                }
                ?>
                <!-- <th class="center"><i class="fa fa-cog"></i></th> -->
            </tr>
        </thead>
        <tbody id="body_table_strata_segment">
            <?php
            $no = 1;
            if ($stmt_mesin):
                while ($row = $sqlsrv_hris->fetch_array($stmt_mesin)):
                    ?>
                    <tr>
                        <td class="center"><?php echo $no; ?></td>
                        <td class="center" style="border-right: #000 1px solid;"><?php echo $row['depo']; ?></td>
                        <?php
                        for ($day = 1; $day <= $numDaysInMonth; $day++) {
                            $dayFormatted = str_pad($day, 2, '0', STR_PAD_LEFT);
                            echo '<td class="center"><a target="_blank" href="?sm=report_validasi_kadepo&act=detail&validate=1&depo='.$row['depo'].'&tgl='.$year.'-'.$month.'-'.$dayFormatted.'">' . $row['validated_tgl_' . $dayFormatted] . '</a></td>';
                            echo '<td class="center"><a target="_blank" href="?sm=report_validasi_kadepo&act=detail&validate=0&depo='.$row['depo'].'&tgl='.$year.'-'.$month.'-'.$dayFormatted.'">' . $row['not_validated_tgl_' . $dayFormatted] . '</a></td>';
                            echo '<td class="center" style="border-right: #000 1px solid;"><a target="_blank" href="?sm=report_validasi_kadepo&act=detail&validate=&depo='.$row['depo'].'&tgl='.$year.'-'.$month.'-'.$dayFormatted.'">' . $row['total_tgl_' . $dayFormatted] . '</a></td>';
                        }
                        ?>
                        <!-- <td class="center">
                        <?php if ($row_mesin['lc_name'] != ""): ?>
                            <i class="ace-icon fa fa-check bigger-120"></i> Validated
                        <?php else: ?>
                            <button class="btn btn-xs btn-white btn-info" onclick="validasi('<?php echo $row_mesin['pin']; ?>', '<?php echo $row_mesin['nik']; ?>', '<?php echo $tanggal; ?>', '<?php echo $row_mesin['nama']; ?>', '<?php echo $_GET['depo']; ?>')"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
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
</div>