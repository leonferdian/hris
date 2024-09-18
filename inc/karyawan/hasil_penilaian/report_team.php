<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_GET['act'] == "report_team"):
    $header = explode(",", "divisi,nama_karyawan");
    $filter = "";
    $filter .= isset($_GET['divisi']) && $_GET['divisi'] != "" ? " and a.divisi = '" . $_GET['divisi'] . "'" : "";
    $filter .= " AND b.score is not null";
    $group_by = " GROUP BY a.[divisi], 
                b.[nama_karyawan]
                WITH ROLLUP";
    $order_by = " ORDER BY
                GROUPING(a.[divisi]),
                a.[divisi],
                GROUPING(b.[nama_karyawan]), 
                b.[nama_karyawan]";
endif;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Laporan Kinerja Karyawan Perindividu</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <a href="?sm=hasil_penilaian"> <i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <h1>
                Laporan Kinerja Karyawan by Divisi
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="widget-box">
                    <div class="widget-header hide">
                        <h4 class="widget-title"></h4>
                        <span class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <h4 class="widget-title"><?php echo isset($_GET['divisi']) && $_GET['divisi'] != "" ? "Divisi: ".$_GET['divisi'] : "All Divisi"; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...
                </span>
                <div id="list_vol">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
                        <thead>
                            <tr>
                                <?php
                                for ($i = 0; $i < count($header); $i++) {
                                    if ($header[$i] == "divisi") {
                                        echo "<th style='text-align:center;vertical-align:middle; background-color:#ddebf7;'>Divisi</th>";
                                    } elseif ($header[$i] == "nama_karyawan") {
                                        echo "<th style='text-align:center;vertical-align:middle; background-color:#ddebf7; border-right: medium solid;'>Nama Karyawan</th>";
                                    }
                                }
                                ?>
                                <th style="vertical-align:middle; text-align:center;">Total Score</th>
                                <th style="vertical-align:middle; text-align:center;">Total Nilai</th>
                                <th style="vertical-align:middle; text-align:center;">Total Score Atasan</th>
                                <th style="vertical-align:middle; text-align:center;">Total Nilai Atasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                                        CASE
                                            WHEN (GROUPING(a.[divisi]) = 1) THEN 'Total'
                                            WHEN a.[divisi] = '' THEN 'UNKNOWN'
                                            ELSE ISNULL(a.[divisi], 'UNKNOWN')
                                        END AS divisi,
                                        CASE
                                            WHEN (GROUPING([nama_karyawan]) = 1) THEN 'Total'
                                            WHEN [nama_karyawan] = '' THEN 'UNKNOWN'
                                            ELSE ISNULL([nama_karyawan], 'UNKNOWN')
                                        END AS nama_karyawan,
                                        SUM(b.[score]) AS score,
                                        SUM(b.[nilai]) AS nilai,
                                        SUM(b.[score_atasan]) AS score_atasan,
                                        SUM(b.[nilai_atasan]) AS nilai_atasan 
                                        FROM [db_hris].[dbo].[table_penilaian_karyawan] a
                                        LEFT JOIN [db_hris].[dbo].[table_penilaian_karyawan_result] b
                                        ON a.id = b.id_penilaian
                                        WHERE 1=1";
                            $sql .= $filter;
                            $sql .= $group_by;
                            $sql .= $order_by;
                            $stmt = $sqlsrv_hris->query($sql);
                            if ($stmt):
                                $countrow = array_fill(0, count($header), 0);
                                while ($row = $sqlsrv_hris->fetch_array($stmt)):

                                    $sql_grade = "";

                                    for ($i = count($header) - 1; $i >= 0; $i--) {
                                        if ($countrow[$i] == 1 && $row[$header[$i]] == "Total") {
                                            $countrow[$i] = 0;
                                            continue 2;
                                        }

                                        if ($row[$header[$i]] != "Total") {
                                            $countrow[$i]++;
                                        } else {
                                            $countrow[$i] = 0;
                                        }
                                    }
                                    echo '<tr>';
                                    $style = "";
                                    $colspan = 1;
                                    $class = "class='td_l1'";
                                    for ($i = 0; $i < count($header); $i++) {
                                        $border = "";
                                        $cell = $row[$header[$i]];
                                        // if ($header[$i] == "deskripsi") {
                                        //     if ($row['proses'] > 0) {
                                        //         $style = "background-color:#FFB7B7;";
                                        //     }
                                        // }
                            
                                        if ($i == count($header) - 1) {
                                            $class = "";
                                            $border = "border-right: medium solid;";
                                        }
                                        if ($row[$header[$i]] == "Total") {
                                            if ($i == 0) {
                                                $cell = "GRAND TOTAL";
                                            } else {
                                                $cell = "Total " . $row[$header[$i - 1]];
                                            }

                                            $colspan = count($header) - $i;
                                            $border = "border-right: medium solid;";

                                            if ($i == 17) {
                                                $style = "background-color:#2987d9;";
                                            } else if ($i == 16) {
                                                $style = "background-color:#f0ad30;";
                                            } else if ($i == 15) {
                                                $style = "background-color:#c289ec;";
                                            } else if ($i == 14) {
                                                $style = "background-color:#e1b6fa;";
                                            } else if ($i == 13) {
                                                $style = "background-color:#bc85d4;";
                                            } else if ($i == 12) {
                                                $style = "background-color:#777bc9;";
                                            } else if ($i == 11) {
                                                $style = "background-color:#ffc499;";
                                            } else if ($i == 10) {
                                                $style = "background-color:#e1ff69;";
                                            } else if ($i == 9) {
                                                $style = "background-color:#c4ff9c;";
                                            } else if ($i == 8) {
                                                $style = "background-color:#b3ff87;";
                                            } else if ($i == 7) {
                                                $style = "background-color:#fce77c;";
                                            } else if ($i == 6) {
                                                $style = "background-color:#d6fff6;";
                                            } else if ($i == 5) {
                                                $style = "background-color:#66eda3;";
                                            } else if ($i == 4) {
                                                $style = "background-color:#7aeff5;";
                                            } else if ($i == 3) {
                                                $style = "background-color:#ffccff;";
                                            } else if ($i == 2) {
                                                $style = "background-color:#82f5a1;";
                                            } else if ($i == 1) {
                                                $style = "background-color:#fcd51e;";
                                            } else if ($i == 0) {
                                                $style = "background-color:#ffb296;";
                                            }
                                        }

                                        echo "<td style='vertical-align:middle; text-align:center;" . $style . $border . "' " . $class . " colspan='" . $colspan . "'>" . $cell . "</td>";
                                        if ($row[$header[$i]] == "Total") {
                                            break;
                                        }
                                    }

                                    $url_header = "";
                                    for ($i = 0; $i < count($header); $i++) {
                                        $url_header .= '&' . $header[$i] . '=' . urlencode($row[$header[$i]]);
                                    }

                                    // Display the data in table columns
                                    echo '<td style="vertical-align:middle; text-align:center;">' . $row['score'] . '</td>';
                                    echo '<td style="vertical-align:middle; text-align:center;">' . $row['nilai'] . '</td>';
                                    echo '<td style="vertical-align:middle; text-align:center;">' . $row['score_atasan'] . '</td>';
                                    echo '<td style="vertical-align:middle; text-align:center;">' . $row['nilai_atasan'] . '</td>';
                                    echo '</tr>';
                                endwhile;
                            else:
                                echo $sql;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').css('width', '220px').select2({ allowClear: true })
            .on('change', function () {
                // $(this).closest('form').validate().element($(this));
            });

        $('.datepicker').datepicker({
            Format: 'YYYY-MM-DD',
            autoclose: true,
            todayHighlight: true
        })
            .prev().on(ace.click_event, function () {
                $(this).next().focus();
            });

        $('#dataForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: 'inc/karyawan/hasil_penilaian/add_edit_delete.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response);
                    // $('#dataForm')[0].reset();
                    if (response.trim() == "Data Berhasil Disimpan") {
                        location.href = "?sm=hasil_penilaian";
                    }
                }
            });
        });

        MergeCommonRows($('.table'));
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
        var $table = $('#table_strata_segment');
        $table.floatThead({
            responsiveContainer: function ($table) {
                return $table.closest('.table-responsive');
            },
            position: 'absolute'

        });
    }
</script>