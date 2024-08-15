<?php
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
?>
<table width="100%" class="table table-striped table-bordered table-hover" id="data_excel">
    <thead>
        <tr>
            <th style="vertical-align:middle; text-align:center; width:100px;">Timestamp</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Score</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Nama Lengkap</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Nomor HP</th>
            <th style="vertical-align:middle; text-align:center; width:100px;">Jabatan yang dilamar</th>
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