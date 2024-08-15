<?php
include('../../../lib/fungsi_rupiah.php');
include('../../../lib/db_sqlserver_ilp.php');
include('../../../lib/db_sqlserver_central_input.php');
// include('../../../lib/db_connect_hris2.php');
set_time_limit(0);
session_start();
// $_SESSION['id_company'] = $_GET['id_company'];
$depo = "";
if (isset($_GET['depo']) && $_GET['depo'] != "") {
    if ($_GET['depo'] == "Blank") {
      $depo = " and b.depo is null";
    } else {
        $depo = " and b.depo = '".$_GET['depo']."'";
    }
}
$url = "";
$tgl = date("Y-m-d", strtotime($_GET['tgl']));
// $depo = $_GET['depo'];
$dayOfWeekNumber = date('N', strtotime($tgl));
// $tahun = $_POST['tahun'];
// $bulan = $_POST['bulan'];
// $url .= "&tahun=".rawurlencode($_POST['tahun'])."&bulan=".rawurlencode($_POST['bulan']);
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
</div>
<i class="ace-icon fa fa-square text-danger" style="color:#ff0000;"></i> Tidak Masuk
<br><br>
<div class="table-responsive" id="list_core">
    <table class="table table-bordered table-hover" id="table_absensi_report">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Depo</th>
                <th>Nama</th>
                <th>Nik</th>
                <th>Email</th>
                <th>Scan Masuk</th>
                <th>Scan Keluar</th>
                <th>Lokasi Checkin</th>
                <th>Lokasi Checkout</th>
                <th>View Absen</th>
            </tr>
        </thead>
        <tbody id="body_table_strata_segment">
            <?php
            $sql = "SELECT DISTINCT
              CONVERT(date, date_create) AS date_create,
              b.depo,
              a.nama_user,
              a.username as email,
              a.nik,
              a.image_absen,
              a.time_check_in,
              a.time_check_out,
              a.status_absensi,
              a.address_check_in,
              a.address_check_out,
              a.lat_check_in,
              a.lng_check_in,
              a.lat_check_out,
              a.lng_check_out,
              a.foto_user,
              a.nama_company
            FROM (SELECT
              a.time_check_in,
              a.time_check_out,
              a.address_check_in,
              a.address_check_out,
              a.status_absensi,
              a.create_by,
              a.date_create,
              a.image_absen,
              b.nama_user,
              b.username,
              b.nik,
              lat_check_in,
              lng_check_in,
              lat_check_out,
              lng_check_out,
              c.foto_user,
              d.nama_company
            FROM [db_ilv_padma].[dbo].[table_absensi] AS a
            LEFT JOIN [db_ilv_padma].[dbo].[table_user] AS b
              ON a.id_user = b.id
            LEFT JOIN [db_ilv_padma].[dbo].[table_user_photo] c
              ON b.id = c.id_user
            LEFT JOIN [db_ilv_padma].[dbo].[table_company] d
              ON a.id_company = d.id
            WHERE a.id_company = '7'
            AND cast(a.date_create as date) = '".$tgl."') a
            LEFT JOIN (SELECT
              *
            FROM (SELECT
              *
            FROM (SELECT
              cab.cab_name AS depo,
              tabel_karyawan.alias AS nama,
              tabel_karyawan.pin,
              tabel_karyawan.nik
            FROM [db_ftm].[dbo].[emp] AS tabel_karyawan
            LEFT JOIN [db_ftm].[dbo].[cabang] cab
              ON tabel_karyawan.cab_id_auto = cab.cab_id_auto
            WHERE tabel_karyawan.emp_status = '0') people
            UNION ALL
            SELECT
              *
            FROM (SELECT
              depo.pembagian3_nama AS depo,
              tabel_karyawan.pegawai_nama AS nama,
              tabel_karyawan.pegawai_pin AS pin,
              tabel_karyawan.pegawai_nip AS nik
            FROM [db_fin_pro].[dbo].[pegawai] AS tabel_karyawan
            LEFT JOIN [db_fin_pro].[dbo].[pembagian3] AS depo
              ON tabel_karyawan.pembagian3_id = depo.pembagian3_id
            WHERE tabel_karyawan.pegawai_status = '1') people_fp) people_all
            GROUP BY depo,
                    nama,
                    pin,
                    nik) b
              ON b.nama LIKE a.nama_user
              OR a.nik = b.nik
            WHERE a.date_create != '' ".$depo."
            GROUP BY b.depo,
                    a.nama_user,
                    a.username,
                    a.nik,
                    a.time_check_in,
                    a.time_check_out,
                    a.image_absen,
                    a.status_absensi,
                    a.address_check_in,
                    a.address_check_out,
                    a.date_create,
                    a.lat_check_in,
                    a.lng_check_in,
                    a.lat_check_out,
                    a.lng_check_out,
                    a.foto_user,
                    a.nama_company,
                    ROLLUP (CONVERT(date, date_create))
            ORDER BY date_create DESC";
            $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            $no = 1;
            while ($row_mesin = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):
            ?>
                <tr>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $no; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['date_create']) ? date_format($row_mesin['date_create'], "Y-m-d") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['depo']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['nama_user']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['nik']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['email']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['time_check_in']) ? date_format($row_mesin['time_check_in'], "H:i") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo isset($row_mesin['time_check_out']) ? date_format($row_mesin['time_check_out'], "H:i:s") : ""; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['address_check_in']; ?></td>
                    <td style="vertical-align:middle; text-align:center;"><?php echo $row_mesin['address_check_out']; ?></td>
                    <td class="center">
                        <?php if ($row_mesin['image_absen'] != ""): ?>
                        <a class="btn btn-white btn-xs btn-purple" onclick="view_iamge('<?php echo $row_mesin['image_absen']; ?>')"> <i class="ace-icon fa fa-picture-o file-image"></i> view</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                $no++;
            endwhile;
            ?>
        </tbody>
    </table>
</div>
<?php #echo $sql_mesin; ?>
<?php sqlsrv_close($conn_ilv); ?>