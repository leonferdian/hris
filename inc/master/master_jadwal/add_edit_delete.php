<?php
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
function validate_input($data)
{
    $data = str_replace("'", "''", $data);
    return $data;
}

if (isset($_POST['act']) && $_POST['act'] == "add_data"):
    $sql = "INSERT INTO [db_hris].[dbo].[table_absensi_mesin_jadwal]
                    VALUES ('" . validate_input($_POST['depo']) . "','" . validate_input($_POST['shift']) . "','" . validate_input($_POST['shift_name']) . "','" . validate_input($_POST['jam_masuk']) . "','" . validate_input($_POST['jam_keluar']) . "','" . validate_input($_POST['vendor']) . "')";
    $result = $sqlsrv_hris->query($sql);
    if ($result) {
        echo "Data Berhasil Ditambahkan";
    } else {
        echo "Data Gagal Ditambahkan `$sql`";
    }
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $sql = "UPDATE [db_hris].[dbo].[table_absensi_mesin_jadwal] SET 
                    depo = '" . validate_input($_POST['depo']) . "',
                    shift = '" . validate_input($_POST['shift']) . "',
                    shift_name = '" . validate_input($_POST['shift_name']) . "',
                    jam_masuk = '" . validate_input($_POST['jam_masuk']) . "',
                    jam_keluar = '" . validate_input($_POST['jam_keluar']) . "',
                    vendor = '" . validate_input($_POST['vendor']) . "'
                    WHERE id = " . $_POST['id_jadwal'];
    $result = $sqlsrv_hris->query($sql);
    if ($result) {
        echo "Data Berhasil Diubah";
    } else {
        echo "Data Gagal Diubah \r\n" . $sql_update;
    }
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql = "DELETE FROM [db_hris].[dbo].[table_absensi_mesin_jadwal] WHERE id = '" . $_POST['kode'] . "'";
    $result = $sqlsrv_hris->query($sql);
    if ($result) {
        echo "Data Berhasil dihapus";
    } else {
        echo "Data Gagal dihapus `$sql`";
    }
elseif ($_POST['act'] == "add_dialog" || $_POST['act'] == "edit_dialog"):
    if ($_POST['act'] == "edit_dialog") {
        $sql_select = "select * from [db_hris].[dbo].[table_absensi_mesin_jadwal] where id = " . $_POST['kode'];
        $stmt_select = $sqlsrv_hris->query($sql_select);
        $row_select = $sqlsrv_hris->fetch_array($stmt_select);
        echo '<input type="hidden" id="id_jadwal" name="id_jadwal" value="' . $_POST['kode'] . '">';
    }
    ?>
    <div class="row" style="width:100%;height:100%; margin-left: 5px;">
        <div class="col-sm-12">
            <!-- <label class="col-sm-3 control-label">Depo</label> -->
            <div class="form-group col-sm-7 hide">
                <select class="form-control input-sm chosen-select" name="slc_depo" id="slc_depo"
                    data-placeholder="Choose a State..." onchange="">
                    <?php
                    $sql = "SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
                            union
                            SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]";
                    $stmt = $sqlsrv_hris->query($sql);
                    while ($depo = $sqlsrv_hris->fetch_array($stmt)):
                        $selected = $_POST['act'] == "edit_dialog" && trim($row_select['depo']) == trim($depo['depo']) ? "selected" : "";
                        ?>
                        <option value="<?php echo $depo['depo']; ?>" <?php echo $selected; ?>>
                            <?php echo $depo['depo']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- <br> -->
            <label class="col-sm-3 control-label">Shift</label>
            <div class="form-group col-sm-7">
                <select class="form-control input-sm chosen-select" name="slc_shift" id="slc_shift"
                    data-placeholder="Choose a State..." onchange="">
                    <?php
                    $sql = "select shift_name, kode, 'ftm' as vendor from [db_ftm].[dbo].[shift]
                            union all
                            select jdw_kerja_m_name as shift_name, jdw_kerja_m_kode as kode, 'fp' as vendor from [db_fin_pro].[dbo].[jdw_kerja_m]";
                    $stmt = $sqlsrv_hris->query($sql);
                    while ($shift1 = $sqlsrv_hris->fetch_array($stmt)):
                        $selected = $_POST['act'] == "edit_dialog" && trim($row_select['shift']) == trim($shift1['kode']) ? "selected" : "";
                        ?>
                        <option value="<?php echo $shift1['kode']; ?>" data-vendor="<?php echo $shift1['vendor']; ?>"
                            data-shiftname="<?php echo $shift1['shift_name']; ?>" <?php echo $selected; ?>>
                            <?php echo $shift1['shift_name']. "(" . $shift1['vendor'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <br>
            <label class="col-sm-3 control-label">Jam Masuk</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_jam_masuk" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['jam_masuk'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
            <br>
            <label class="col-sm-3 control-label">Jam Keluar</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_jam_keluar" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['jam_keluar'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
            <br>
            <label class="col-sm-3 control-label">Start Scan Masuk</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_start_scan_masuk" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['start_scan_masuk'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
            <br>
            <label class="col-sm-3 control-label">Start Scan Keluar</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_start_scan_keluar" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['start_scan_keluar'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
            <br>
            <label class="col-sm-3 control-label">End Scan Masuk</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_end_scan_masuk" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['end_scan_masuk'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
            <br>
            <label class="col-sm-3 control-label">End Scan Keluar</label>
            <div class="form-group col-sm-7">
                <div class="input-group bootstrap-timepicker">
                    <input id="txt_end_scan_keluar" type="text" class="form-control timepicker1"
                        value="<?php echo $_POST['act'] == "edit_dialog" ? date_format($row_select['end_scan_keluar'], "H:i") : ""; ?>" />
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>