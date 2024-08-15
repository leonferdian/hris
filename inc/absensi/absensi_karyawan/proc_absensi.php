<?php
include ('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_POST['act'] == 'sinkron_absen'):
    $tgl = date("Y-m-d", strtotime($_POST['tgl']));
    $dayOfWeekNumber = date('N', strtotime($tgl));
    $sql_cek = "SELECT * FROM [db_hris].[dbo].[table_absensi_log] WHERE [date_absen] = '" . $tgl . "'";
    $stmt_cek = $sqlsrv_hris->query($sql_cek);
    $num_cek = $sqlsrv_hris->num_rows($stmt_cek);

    if ($num_cek > 0):
        $sql_clean = "DELETE from [db_hris].[dbo].[table_absensi_log] WHERE [date_absen] = '" . $tgl . "'";
        $stmt_clean = $sqlsrv_hris->query($sql_clean);
    endif;

    $sql = "INSERT into [db_hris].[dbo].[table_absensi_log]
        SELECT
        absensi.depo
        ,absensi.pin
        ,absensi.nik
        ,absensi.nama
        ,absensi.func_name
        ,absensi.tanggal
        ,absensi.kode
        ,absensi.shift_ot
        ,absensi.shift
        ,absensi.shift_name
        ,absensi.jam_masuk
        ,absensi.jam_keluar
        ,absensi.start_scan_masuk
        ,absensi.start_scan_keluar
        ,absensi.end_scan_masuk
        ,absensi.end_scan_keluar
        ,absensi.time_scan_in
        ,absensi.time_scan_out
        ,val.keterangan
        ,val.nilai
        ,val.verifikasi_apps
        ,val.lc_name AS leave_category
        ,RIGHT('00' + CAST(telat_hour AS VARCHAR(2)), 2) + ':' + RIGHT('00' + CAST(telat_minute AS VARCHAR(2)), 2) AS telat
        ,RIGHT('00' + CAST(p_cepat_hour AS VARCHAR(2)), 2) + ':' + RIGHT('00' + CAST(p_cepat_minute AS VARCHAR(2)), 2) AS p_cepat
        ,RIGHT('00' + CAST(durasi_hour AS VARCHAR(2)), 2) + ':' + RIGHT('00' + CAST(durasi_minute AS VARCHAR(2)), 2) AS durasi
        ,'" . $tgl . "' as date_absen
        FROM (SELECT
            people.depo
        ,people.pin
        ,people.nik
        ,people.nama
        ,absensi_all.func_name
        ,absensi_all.tanggal
        ,absensi_all.kode
        ,absensi_all.shift_ot
        ,absensi_all.shift
        ,absensi_all.shift_name
        ,absensi_all.jam_masuk
        ,absensi_all.jam_keluar
        ,absensi_all.start_scan_masuk
        ,absensi_all.start_scan_keluar
        ,absensi_all.end_scan_masuk
        ,absensi_all.end_scan_keluar
        ,absensi_all.time_scan_in
        ,absensi_all.time_scan_out
        ,CASE
            WHEN absensi_all.time_scan_in > absensi_all.jam_masuk THEN DATEDIFF(HOUR, absensi_all.jam_masuk, absensi_all.time_scan_in)
            END AS telat_hour
        ,CASE
            WHEN absensi_all.time_scan_in > absensi_all.jam_masuk THEN DATEDIFF(MINUTE, absensi_all.jam_masuk, absensi_all.time_scan_in) % 60
            END AS telat_minute
        ,CASE
            WHEN absensi_all.time_scan_out < absensi_all.jam_keluar THEN DATEDIFF(HOUR, absensi_all.time_scan_out, absensi_all.jam_keluar)
            END AS p_cepat_hour
        ,CASE
            WHEN absensi_all.time_scan_out < absensi_all.jam_keluar THEN DATEDIFF(MINUTE, absensi_all.time_scan_out, absensi_all.jam_keluar) % 60
            END AS p_cepat_minute
        ,CASE
            WHEN absensi_all.time_scan_in < absensi_all.time_scan_out THEN DATEDIFF(HOUR, absensi_all.time_scan_in, absensi_all.time_scan_out)
            ELSE DATEDIFF(HOUR, absensi_all.time_scan_out, absensi_all.time_scan_in)
            END AS durasi_hour
        ,CASE
            WHEN absensi_all.time_scan_in < absensi_all.time_scan_out THEN DATEDIFF(MINUTE, absensi_all.time_scan_in, absensi_all.time_scan_out) % 60
            ELSE DATEDIFF(MINUTE, absensi_all.time_scan_out, absensi_all.time_scan_in) % 60
            END AS durasi_minute
        FROM (SELECT
            *
            FROM (SELECT
                cab.cab_name AS depo
            ,tabel_karyawan.alias AS nama
            ,tabel_karyawan.pin
            ,tabel_karyawan.nik
            FROM [db_ftm].[dbo].[emp] AS tabel_karyawan
            LEFT JOIN [db_ftm].[dbo].[cabang] cab
                ON tabel_karyawan.cab_id_auto = cab.cab_id_auto
            WHERE tabel_karyawan.emp_status = '0'
            
            UNION ALL
            SELECT
                depo.pembagian3_nama AS depo
            ,tabel_karyawan.pegawai_nama AS nama
            ,tabel_karyawan.pegawai_pin AS pin
            ,tabel_karyawan.pegawai_nip AS nik
            FROM [db_fin_pro].[dbo].[pegawai] AS tabel_karyawan
            LEFT JOIN [db_fin_pro].[dbo].[pembagian3] AS depo
                ON tabel_karyawan.pembagian3_id = depo.pembagian3_id
            WHERE tabel_karyawan.pegawai_status = '1'
            ) people_all
            GROUP BY depo
                    ,nama
                    ,pin
                    ,nik) people
        LEFT JOIN (SELECT
            depo
            ,pin
            ,nik
            ,nama
            ,func_name
            ,tanggal
            ,kode
            ,shift_ot
            ,shift
            ,shift_name
            ,jam_masuk
            ,jam_keluar
            ,start_scan_masuk
            ,start_scan_keluar
            ,end_scan_masuk
            ,end_scan_keluar
            ,time_scan_in
            ,time_scan_out
            FROM (SELECT
                CASE
                WHEN scan_in.depo IS NOT NULL THEN scan_in.depo
                ELSE scan_out.depo
                END AS depo
            ,CASE
                WHEN scan_in.pin IS NOT NULL THEN scan_in.pin
                ELSE scan_out.pin
                END AS pin
            ,CASE
                WHEN scan_in.nik IS NOT NULL THEN scan_in.nik
                ELSE scan_out.nik
                END AS nik
            ,CASE
                WHEN scan_in.nama IS NOT NULL THEN scan_in.nama
                ELSE scan_out.nama
                END AS nama
            ,CASE
                WHEN scan_in.func_name IS NOT NULL THEN scan_in.func_name
                ELSE scan_out.func_name
                END AS func_name
            ,CASE
                WHEN scan_in.tanggal IS NOT NULL THEN scan_in.tanggal
                ELSE scan_out.tanggal
                END AS tanggal
            ,CASE
                WHEN scan_in.kode IS NOT NULL THEN scan_in.kode
                ELSE scan_out.kode
                END AS kode
            ,CASE
                WHEN scan_in.shift_ot IS NOT NULL THEN scan_in.shift_ot
                ELSE scan_out.shift_ot
                END AS shift_ot
            ,CASE
                WHEN scan_in.[shift] IS NOT NULL THEN scan_in.[shift]
                ELSE scan_out.[shift]
                END AS shift
            ,CASE
                WHEN scan_in.[shift_name] IS NOT NULL THEN scan_in.[shift_name]
                ELSE scan_out.[shift_name]
                END AS shift_name
            ,CASE
                WHEN scan_in.[jam_masuk] IS NOT NULL THEN scan_in.[jam_masuk]
                ELSE scan_out.[jam_masuk]
                END AS jam_masuk
            ,CASE
                WHEN scan_in.[jam_keluar] IS NOT NULL THEN scan_in.[jam_keluar]
                ELSE scan_out.[jam_keluar]
                END AS jam_keluar
            ,CASE
                WHEN scan_in.[start_scan_masuk] IS NOT NULL THEN scan_in.[start_scan_masuk]
                ELSE scan_out.[start_scan_masuk]
                END AS start_scan_masuk
            ,CASE
                WHEN scan_in.[start_scan_keluar] IS NOT NULL THEN scan_in.[start_scan_keluar]
                ELSE scan_out.[start_scan_keluar]
                END AS start_scan_keluar
            ,CASE
                WHEN scan_in.[end_scan_masuk] IS NOT NULL THEN scan_in.[end_scan_masuk]
                ELSE scan_out.[end_scan_masuk]
                END AS end_scan_masuk
            ,CASE
                WHEN scan_in.[end_scan_keluar] IS NOT NULL THEN scan_in.[end_scan_keluar]
                ELSE scan_out.[end_scan_keluar]
                END AS end_scan_keluar
            ,scan_in.time_scan AS time_scan_in
            ,scan_out.time_scan AS time_scan_out
            FROM (SELECT
                cab.cab_name AS depo
                ,tabel_karyawan.pin
                ,tabel_karyawan.func_id_auto
                ,tabel_karyawan.nik
                ,tabel_karyawan.alias AS nama
                ,fc.func_name
                ,CAST(att_log.scan_date AS DATE) AS tanggal
                ,shift.kode
                ,shift.shift_ot
                ,jadwal.[shift]
                ,jadwal.[shift_name]
                ,jadwal.[jam_masuk]
                ,jadwal.[jam_keluar]
                ,jadwal.[start_scan_masuk]
                ,jadwal.[start_scan_keluar]
                ,jadwal.[end_scan_masuk]
                ,jadwal.[end_scan_keluar]
                ,MIN(CAST(att_log.scan_date AS TIME(0))) AS time_scan
                FROM [db_ftm].[dbo].[emp] AS tabel_karyawan
                LEFT JOIN [db_ftm].[dbo].[cabang] cab
                ON tabel_karyawan.cab_id_auto = cab.cab_id_auto
                LEFT JOIN (SELECT
                    *
                FROM [db_ftm].[dbo].[table_shift_emp]
                WHERE day_order = " . $dayOfWeekNumber . ") shift
                ON tabel_karyawan.pin = shift.pin
                AND tabel_karyawan.nik = shift.nik
                AND tabel_karyawan.alias = shift.alias
                LEFT JOIN (SELECT
                    *
                FROM [db_hris].[dbo].[table_absensi_mesin_jadwal]
                WHERE vendor = 'ftm') jadwal
                ON shift.kode = jadwal.shift
                LEFT JOIN [db_ftm].[dbo].[func] fc
                ON tabel_karyawan.func_id_auto = fc.func_id_auto
                LEFT JOIN OPENQUERY(MYSQL_FTM, 'select * from att_log where cast(scan_date as date) = ''" . $tgl . "''') att_log
                ON tabel_karyawan.pin COLLATE SQL_Latin1_General_CP1_CI_AS = att_log.pin COLLATE SQL_Latin1_General_CP1_CI_AS
                WHERE tabel_karyawan.emp_status = '0'
                AND CAST(att_log.scan_date AS TIME(0)) >= jadwal.start_scan_masuk
                AND CAST(att_log.scan_date AS TIME(0)) < jadwal.start_scan_keluar
                GROUP BY cab.cab_name
                        ,tabel_karyawan.pin
                        ,tabel_karyawan.func_id_auto
                        ,tabel_karyawan.nik
                        ,tabel_karyawan.alias
                        ,fc.func_name
                        ,CAST(att_log.scan_date AS DATE)
                        ,shift.kode
                        ,shift.shift_ot
                        ,jadwal.[shift]
                        ,jadwal.[shift_name]
                        ,jadwal.[jam_masuk]
                        ,jadwal.[jam_keluar]
                        ,jadwal.[start_scan_masuk]
                        ,jadwal.[start_scan_keluar]
                        ,jadwal.[end_scan_masuk]
                        ,jadwal.[end_scan_keluar]) scan_in
            FULL OUTER JOIN (SELECT
                cab.cab_name AS depo
                ,tabel_karyawan.pin
                ,tabel_karyawan.func_id_auto
                ,tabel_karyawan.nik
                ,tabel_karyawan.alias AS nama
                ,fc.func_name
                ,CAST(att_log.scan_date AS DATE) AS tanggal
                ,shift.kode
                ,shift.shift_ot
                ,jadwal.[shift]
                ,jadwal.[shift_name]
                ,jadwal.[jam_masuk]
                ,jadwal.[jam_keluar]
                ,jadwal.[start_scan_masuk]
                ,jadwal.[start_scan_keluar]
                ,jadwal.[end_scan_masuk]
                ,jadwal.[end_scan_keluar]
                ,MAX(CAST(att_log.scan_date AS TIME(0))) AS time_scan
                FROM [db_ftm].[dbo].[emp] AS tabel_karyawan
                LEFT JOIN [db_ftm].[dbo].[cabang] cab
                ON tabel_karyawan.cab_id_auto = cab.cab_id_auto
                LEFT JOIN (SELECT
                    *
                FROM [db_ftm].[dbo].[table_shift_emp]
                WHERE day_order = " . $dayOfWeekNumber . ") shift
                ON tabel_karyawan.pin = shift.pin
                AND tabel_karyawan.nik = shift.nik
                AND tabel_karyawan.alias = shift.alias
                LEFT JOIN (SELECT
                    *
                FROM [db_hris].[dbo].[table_absensi_mesin_jadwal]
                WHERE vendor = 'ftm') jadwal
                ON shift.kode = jadwal.shift
                LEFT JOIN [db_ftm].[dbo].[func] fc
                ON tabel_karyawan.func_id_auto = fc.func_id_auto
                LEFT JOIN OPENQUERY(MYSQL_FTM, 'select * from att_log where cast(scan_date as date) = ''" . $tgl . "''') att_log
                ON tabel_karyawan.pin COLLATE SQL_Latin1_General_CP1_CI_AS = att_log.pin COLLATE SQL_Latin1_General_CP1_CI_AS
                WHERE tabel_karyawan.emp_status = '0'
                AND CAST(att_log.scan_date AS TIME(0)) >= jadwal.start_scan_keluar
                AND CAST(att_log.scan_date AS TIME(0)) <= jadwal.end_scan_keluar
                GROUP BY cab.cab_name
                        ,tabel_karyawan.pin
                        ,tabel_karyawan.func_id_auto
                        ,tabel_karyawan.nik
                        ,tabel_karyawan.alias
                        ,fc.func_name
                        ,CAST(att_log.scan_date AS DATE)
                        ,shift.kode
                        ,shift.shift_ot
                        ,jadwal.[shift]
                        ,jadwal.[shift_name]
                        ,jadwal.[jam_masuk]
                        ,jadwal.[jam_keluar]
                        ,jadwal.[start_scan_masuk]
                        ,jadwal.[start_scan_keluar]
                        ,jadwal.[end_scan_masuk]
                        ,jadwal.[end_scan_keluar]) scan_out
                ON scan_in.depo = scan_out.depo
                AND scan_in.tanggal = scan_out.tanggal
                AND scan_in.pin = scan_out.pin
                AND scan_in.nik = scan_out.nik
                AND scan_in.kode = scan_out.kode) absensi
            UNION ALL
            SELECT
            depo
            ,pin
            ,nik
            ,nama
            ,func_name
            ,tanggal
            ,kode
            ,shift_ot
            ,shift
            ,shift_name
            ,jam_masuk
            ,jam_keluar
            ,start_scan_masuk
            ,start_scan_keluar
            ,end_scan_masuk
            ,end_scan_keluar
            ,time_scan_in
            ,time_scan_out
            FROM (SELECT
                CASE
                WHEN scan_in.depo IS NOT NULL THEN scan_in.depo
                ELSE scan_out.depo
                END AS depo
            ,CASE
                WHEN scan_in.pin IS NOT NULL THEN scan_in.pin
                ELSE scan_out.pin
                END AS pin
            ,CASE
                WHEN scan_in.nik IS NOT NULL THEN scan_in.nik
                ELSE scan_out.nik
                END AS nik
            ,CASE
                WHEN scan_in.nama IS NOT NULL THEN scan_in.nama
                ELSE scan_out.nama
                END AS nama
            ,'' func_name
            ,CASE
                WHEN scan_in.tanggal IS NOT NULL THEN scan_in.tanggal
                ELSE scan_out.tanggal
                END AS tanggal
            ,CASE
                WHEN scan_in.kode IS NOT NULL THEN scan_in.kode
                ELSE scan_out.kode
                END AS kode
            ,CASE
                WHEN scan_in.shift_ot IS NOT NULL THEN scan_in.shift_ot
                ELSE scan_out.shift_ot
                END AS shift_ot
            ,CASE
                WHEN scan_in.[shift] IS NOT NULL THEN scan_in.[shift]
                ELSE scan_out.[shift]
                END AS shift
            ,CASE
                WHEN scan_in.[shift_name] IS NOT NULL THEN scan_in.[shift_name]
                ELSE scan_out.[shift_name]
                END AS shift_name
            ,CASE
                WHEN scan_in.[jam_masuk] IS NOT NULL THEN scan_in.[jam_masuk]
                ELSE scan_out.[jam_masuk]
                END AS jam_masuk
            ,CASE
                WHEN scan_in.[jam_keluar] IS NOT NULL THEN scan_in.[jam_keluar]
                ELSE scan_out.[jam_keluar]
                END AS jam_keluar
            ,CASE
                WHEN scan_in.[start_scan_masuk] IS NOT NULL THEN scan_in.[start_scan_masuk]
                ELSE scan_out.[start_scan_masuk]
                END AS start_scan_masuk
            ,CASE
                WHEN scan_in.[start_scan_keluar] IS NOT NULL THEN scan_in.[start_scan_keluar]
                ELSE scan_out.[start_scan_keluar]
                END AS start_scan_keluar
            ,CASE
                WHEN scan_in.[end_scan_masuk] IS NOT NULL THEN scan_in.[end_scan_masuk]
                ELSE scan_out.[end_scan_masuk]
                END AS end_scan_masuk
            ,CASE
                WHEN scan_in.[end_scan_keluar] IS NOT NULL THEN scan_in.[end_scan_keluar]
                ELSE scan_out.[end_scan_keluar]
                END AS end_scan_keluar
            ,scan_in.time_scan AS time_scan_in
            ,scan_out.time_scan AS time_scan_out
            FROM (SELECT
                depo.pembagian3_nama AS depo
                ,tabel_karyawan.pegawai_pin AS pin
                ,tabel_karyawan.pegawai_nip AS nik
                ,tabel_karyawan.pegawai_nama AS nama
                ,CAST(att_log.scan_date AS DATE) AS tanggal
                ,shift.kode
                ,shift.shift_ot
                ,jadwal.[shift]
                ,jadwal.[shift_name]
                ,jadwal.[jam_masuk]
                ,jadwal.[jam_keluar]
                ,jadwal.[start_scan_masuk]
                ,jadwal.[start_scan_keluar]
                ,jadwal.[end_scan_masuk]
                ,jadwal.[end_scan_keluar]
                ,MIN(CAST(att_log.scan_date AS TIME(0))) AS time_scan
                FROM [db_fin_pro].[dbo].[pegawai] AS tabel_karyawan
                LEFT JOIN [db_fin_pro].[dbo].[pembagian3] AS depo
                ON tabel_karyawan.pembagian3_id = depo.pembagian3_id
                LEFT JOIN (SELECT
                    *
                FROM [db_fin_pro].[dbo].[table_shift_pegawai]
                WHERE day_order = " . $dayOfWeekNumber . ") shift
                ON tabel_karyawan.pegawai_pin = shift.pin
                AND tabel_karyawan.pegawai_nip = shift.nik
                AND tabel_karyawan.pegawai_nama = shift.alias
                LEFT JOIN (SELECT
                    *
                FROM [db_hris].[dbo].[table_absensi_mesin_jadwal]
                WHERE vendor = 'fp') jadwal
                ON shift.kode = jadwal.shift
                LEFT JOIN OPENQUERY(MYSQL_FP, 'select * from att_log where cast(scan_date as date) = ''" . $tgl . "''') att_log
                ON tabel_karyawan.pegawai_pin COLLATE SQL_Latin1_General_CP1_CI_AS = att_log.pin COLLATE SQL_Latin1_General_CP1_CI_AS
                WHERE tabel_karyawan.pegawai_status = '1'
                AND CAST(att_log.scan_date AS TIME(0)) >= jadwal.start_scan_masuk
                AND CAST(att_log.scan_date AS TIME(0)) < jadwal.start_scan_keluar
                GROUP BY depo.pembagian3_nama
                        ,tabel_karyawan.pegawai_pin
                        ,tabel_karyawan.pegawai_nip
                        ,tabel_karyawan.pegawai_nama
                        ,CAST(att_log.scan_date AS DATE)
                        ,shift.kode
                        ,shift.shift_ot
                        ,jadwal.[shift]
                        ,jadwal.[shift_name]
                        ,jadwal.[jam_masuk]
                        ,jadwal.[jam_keluar]
                        ,jadwal.[start_scan_masuk]
                        ,jadwal.[start_scan_keluar]
                        ,jadwal.[end_scan_masuk]
                        ,jadwal.[end_scan_keluar]) scan_in
            FULL OUTER JOIN (SELECT
                depo.pembagian3_nama AS depo
                ,tabel_karyawan.pegawai_pin AS pin
                ,tabel_karyawan.pegawai_nip AS nik
                ,tabel_karyawan.pegawai_nama AS nama
                ,CAST(att_log.scan_date AS DATE) AS tanggal
                ,shift.kode
                ,shift.shift_ot
                ,jadwal.[shift]
                ,jadwal.[shift_name]
                ,jadwal.[jam_masuk]
                ,jadwal.[jam_keluar]
                ,jadwal.[start_scan_masuk]
                ,jadwal.[start_scan_keluar]
                ,jadwal.[end_scan_masuk]
                ,jadwal.[end_scan_keluar]
                ,MIN(CAST(att_log.scan_date AS TIME(0))) AS time_scan
                FROM [db_fin_pro].[dbo].[pegawai] AS tabel_karyawan
                LEFT JOIN [db_fin_pro].[dbo].[pembagian3] AS depo
                ON tabel_karyawan.pembagian3_id = depo.pembagian3_id
                LEFT JOIN (SELECT
                    *
                FROM [db_fin_pro].[dbo].[table_shift_pegawai]
                WHERE day_order = " . $dayOfWeekNumber . ") shift
                ON tabel_karyawan.pegawai_pin = shift.pin
                AND tabel_karyawan.pegawai_nip = shift.nik
                AND tabel_karyawan.pegawai_nama = shift.alias
                LEFT JOIN (SELECT
                    *
                FROM [db_hris].[dbo].[table_absensi_mesin_jadwal]
                WHERE vendor = 'fp') jadwal
                ON shift.kode = jadwal.shift
                LEFT JOIN OPENQUERY(MYSQL_FP, 'select * from att_log where cast(scan_date as date) = ''" . $tgl . "''') att_log
                ON tabel_karyawan.pegawai_pin COLLATE SQL_Latin1_General_CP1_CI_AS = att_log.pin COLLATE SQL_Latin1_General_CP1_CI_AS
                WHERE tabel_karyawan.pegawai_status = '1'
                AND CAST(att_log.scan_date AS TIME(0)) >= jadwal.start_scan_keluar
                AND CAST(att_log.scan_date AS TIME(0)) <= jadwal.end_scan_keluar
                GROUP BY depo.pembagian3_nama
                        ,tabel_karyawan.pegawai_pin
                        ,tabel_karyawan.pegawai_nip
                        ,tabel_karyawan.pegawai_nama
                        ,CAST(att_log.scan_date AS DATE)
                        ,shift.kode
                        ,shift.shift_ot
                        ,jadwal.[shift]
                        ,jadwal.[shift_name]
                        ,jadwal.[jam_masuk]
                        ,jadwal.[jam_keluar]
                        ,jadwal.[start_scan_masuk]
                        ,jadwal.[start_scan_keluar]
                        ,jadwal.[end_scan_masuk]
                        ,jadwal.[end_scan_keluar]) scan_out
                ON scan_in.depo = scan_out.depo
                AND scan_in.tanggal = scan_out.tanggal
                AND scan_in.pin = scan_out.pin
                AND scan_in.nik = scan_out.nik
                AND scan_in.kode = scan_out.kode) absensi) absensi_all
            ON people.depo = absensi_all.depo
            AND people.nama = absensi_all.nama
            AND people.pin = absensi_all.pin
            AND people.nik = absensi_all.nik) absensi
        LEFT JOIN (SELECT
            a.*
        ,b.leave_category AS lc_name
        FROM [db_hris].[dbo].[table_absensi_validasi] a
        LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b
            ON a.leave_category = b.id
        WHERE a.date_absen = '" . $tgl . "') val
        ON absensi.depo = val.depo
            AND absensi.pin = val.pin
            AND absensi.nik = val.nik
        WHERE absensi.depo IS NOT NULL
        ORDER BY absensi.depo, absensi.nama, absensi.tanggal, absensi.time_scan_in, absensi.time_scan_out";
    $result = $sqlsrv_hris->query($sql);
    if ($result) {
        echo "Data Berhasil Disinkron";
    } else {
        echo "Data Gagal Disinkron `$sql`";
    }
elseif ($act == "save_foto"):
    $nama_foto = "";
    // for($x = 0; $x < $_POST['total_foto']; $x++){
    if (isset($_FILES['file_foto']['name'])) {
        $nama_foto = $_POST['nama_foto'] . "." . pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['file_foto']['tmp_name'], "../../../image_upload/list_foto_pribadi/" . $_POST['nama_foto'] . "." . pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION));
    }
    // }

    if ($_POST['total_foto'] > 0) {
        $sql_cek = "select foto_user from table_user a left join table_user_photo b on a.id = b.id_user where b.id_user = '" . $_POST['id_user'] . "'";
        $stmt_cek = sqlsrv_query($conn_ilv, $sql_cek, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $num_row = sqlsrv_num_rows($stmt_cek);

        $sql = "";
        if ($num_row > 0) {
            $sql = "update table_user_photo set foto_user = '" . $nama_foto . "' where id_user = '" . $_POST['id_user'] . "'";
        } else {
            $sql = "insert into table_user_photo (id_user, foto_user, date_create, date_update) values ('" . $_POST['id_user'] . "','" . $nama_foto . "',getdate(),getdate())";
        }
        $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        if ($stmt) {
            echo "Foto berhasil diupload";
        } else {
            "error: foto gagal diupload `$sql`";
        }
    }
endif;