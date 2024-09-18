<?php
session_start();
ob_start();
include('../../lib/db_sqlserver_ilp.php');

if ($_POST['act'] == "save_aktifitas") {
    define('ARR_MEMBER', $_POST['member']);
    $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : $_SESSION['id_user_ilp'];

    $sql_cek = "select * from table_aktifitas where nmr_aktifitas = '" . $_POST['nmr_aktifitas'] . "'";
    $stmt_cek = sqlsrv_query($conn_ilv, $sql_cek, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $num_row_cek = sqlsrv_num_rows($stmt_cek);

    $sql = "insert into table_aktifitas 
            (nmr_aktifitas
            ,jabatan
            ,tanggal
            ,id_company
            ,date_create
            ,create_by
            ,date_update
            ,update_by) 
            values 
            ('" . $_POST['nmr_aktifitas'] . "', 
            '" . $_POST['jabatan'] . "',
            '" . date("Y-m-d", strtotime($_POST['tanggal'])) . "',
            '" . $_POST['id_company'] . "',
            getdate(), 
            '" . $id_user . "',
            getdate(), 
            '" . $id_user . "')";

    if ($_POST['act_aktifitas'] == "edit_aktifitas" || $num_row_cek > 0) {

        $tanggal = $_POST['tanggal'] != "" ? ",tanggal = '" . date("Y-m-d", strtotime($_POST['tanggal'])) . "'" : "";
        $sql = "update table_aktifitas set
            jabatan = '" . $_POST['jabatan'] . "'
            " . $tanggal . "
            ,date_update = getdate()
            ,update_by = '" . $id_user . "'
            where id = '" . $_POST['id_aktifitas'] . "'";
    }

    $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

    if ($stmt) {

        if ($_POST['act_aktifitas'] == "add_aktifitas") {

            $isi_timeline = '<p>
                                <i>Menambahkan aktifitas</i> <i class="fa fa-calendar"> '.date("d/m/Y", strtotime($_POST['tanggal'])).'</i>
                            </p>';
            $sql_log = "insert into table_timeline (kategori,isi_timeline,id_relasi,id_company,photo_timeline,create_by,date_create) values ('aktifitas','" . $isi_timeline . "','" . $_POST['nmr_aktifitas'] . "','" . $_POST['id_company'] . "','','" . $id_user . "',getdate())";
            $stmt_log = sqlsrv_query($conn_ilv, $sql_log, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            if (!$stmt_log) {
                echo "Error add log timeline `$sql_log` \n";
            }

            if (isset($_POST['complain_nomor']) && $_POST['complain_nomor'] != "") {
                $sql_ticket_agenda = "insert into table_aktifitas_agenda (company_id,nmr_aktifitas,complain_nomor) values ('" . $_POST['id_company'] . "','" . $_POST['nmr_aktifitas'] . "','".$_POST['complain_nomor']."')";
                $stmt_ticket_agenda = sqlsrv_query($conn_ilv, $sql_ticket_agenda, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                if (!$stmt_ticket_agenda) {
                    echo "Error add to agenda `$sql_ticket_agenda` \n";
                }
            }

            if(isset($_POST['member']) && $_POST['member'] != ""):
                foreach(ARR_MEMBER as $member):
                    $sql_member = "insert into [db_ilv_padma].[dbo].[table_aktifitas_member] ([nmr_aktifitas], [member]) values ('" . $_POST['nmr_aktifitas'] . "', '" . $member . "')";
                    $stmt_member = sqlsrv_query($conn_ilv, $sql_member, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                endforeach;
            endif;
        }

        echo "Data berhasil disimpan";
    } else {
        echo "Data gagal disimpan : `$sql`";
    }
} else if ($_POST['act'] == "save_aktifitas_detail") {

    $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : $_SESSION['id_user_ilp'];

    $sql = "insert into table_aktifitas_detail (nmr_aktifitas, detail_aktifitas, tanggal, jam_awal, jam_akhir, status, create_by, date_create, update_by, date_update) values ('" . $_POST['nmr_aktifitas'] . "', '" . $_POST['detail_aktifitas'] . "', '" . $_POST['tanggal'] . "', '" . $_POST['jam_awal'] . "', '" . $_POST['jam_akhir'] . "', '" . $_POST['status'] . "', '" . $id_user . "', getdate(), '" . $id_user. "', getdate())";

    if ($_POST['id_detail'] != "") {
        $sql = "update table_aktifitas_detail set detail_aktifitas = '" . $_POST['detail_aktifitas'] . "', jam_awal = '" . $_POST['jam_awal'] . "', jam_akhir = '" . $_POST['jam_akhir'] . "', status = '" . $_POST['status'] . "', update_by = '" . $id_user . "', date_update = getdate() where id =" . $_POST['id_detail'];
    }

    $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

    if ($stmt) {

        if (isset($_POST['arr_del']) && $_POST['arr_del'] > 0) {
            foreach ($_POST['arr_del'] as $id_del) {
                $sql_del = "delete from table_aktifitas_detail where id = " . $id_del;
                $stmt_del = sqlsrv_query($conn_ilv, $sql_del, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                if (!$stmt_del) {
                    echo "Gagal menghapus detail : `$sql_del` \n";
                }
            }
        }

        echo "Data detail berhasil disimpan \n";
    } else {
        echo "Data detail gagal disimpan : `$sql` \n";
    }
} else if ($_POST['act'] == "del_aktifitas") {

    $sql = "delete from table_aktifitas where nmr_aktifitas = '" . $_POST['nmr_aktifitas'] . "'";

    $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

    if ($stmt) {

        $sql_rmv_detail = "delete from table_aktifitas_detail where nmr_aktifitas = '" . $_POST['nmr_aktifitas'] . "'";

        $stmt_rmv_detail = sqlsrv_query($conn_ilv, $sql_rmv_detail, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        if (!$stmt_rmv_detail) {
            echo "Data detail gagal dihapus : `$sql_rmv_detail` \n";
        }

        echo "Data berhasil dihapus \n";
    } else {
        echo "Data gagal dihapus : `$sql` \n";
    }
} else if ($_POST['act'] == "del_detail_aktifitas") {

    $sql = "delete from table_aktifitas_detail where id = " . $_POST['id_detail'];

    $stmt = sqlsrv_query($conn_ilv, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

    if ($stmt) {

        $sql_rmv_detail = "delete from table_aktifitas_detail where nmr_aktifitas = '" . $_POST['nmr_aktifitas'] . "'";

        $stmt_rmv_detail = sqlsrv_query($conn_ilv, $sql_rmv_detail, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        if (!$stmt_rmv_detail) {
            echo "Data detail gagal dihapus : `$sql_rmv_detail` \n";
        }

        echo "Data berhasil dihapus \n";
    } else {
        echo "Data gagal dihapus : `$sql` \n";
    }
} else if ($_POST['act'] == "list_event") {
    $sql_select_detail = "SELECT a.*, b.id as id_detail, b.detail_aktifitas, b.jam_awal, b.jam_akhir, b.status FROM table_aktifitas a left join table_aktifitas_detail b on a.nmr_aktifitas = b.nmr_aktifitas where id_company = '" . $_SESSION['id_company'] . "' and a.create_by = '" . $_SESSION['id_user_ilp'] . "' and a.tanggal = '" . date("Y-m-d", strtotime($_POST['tanggal'])) . "' ORDER BY date_create asc";
    $stmt_select_detail = sqlsrv_query($conn_ilv, $sql_select_detail, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $no = 0;
?>
    <div class="row">
        <div class="col-xs-12">
            <!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->

            <!-- <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div> -->
            <div class="table-header">
                List agenda <?php echo date("l, d F Y", strtotime($_POST['tanggal'])); ?>
            </div>

            <!-- div.table-responsive -->

            <!-- div.dataTables_borderWrap -->
            <div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#.</th>
                            <th>Event</th>
                            <th class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>Jam Awal</th>
                            <th class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>Jam Akhir</th>
                            <th class="hidden-480">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            while ($row_detail = sqlsrv_fetch_array($stmt_select_detail, SQLSRV_FETCH_ASSOC)) { 
                                $no++;
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row_detail['detail_aktifitas']; ?></td>
                                <td class="hidden-480"><span class="label label-sm label-primary"><?php echo $row_detail['jam_awal']; ?></span></td>
                                <td class="hidden-480"><span class="label label-sm label-danger"><?php echo $row_detail['jam_akhir']; ?></span></td>
                                <td class="hidden-480"><?php echo $row_detail['status']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
    // echo $sql_select_detail;
} else if ($_POST['act'] == "merge_agenda") {
    $sqlcek_aktifitas = "SELECT top 1* FROM table_aktifitas where tanggal = '".$_POST['tanggal']."' and create_by = '".$_POST['create_by']."' and id_company = '" . $_POST['id_company'] . "'";
    $stmt1 = sqlsrv_query($conn_ilv, $sqlcek_aktifitas, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $num_row1 = sqlsrv_num_rows($stmt1);
    
    if ($num_row1 > 0) {
        $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
        $new_nmr_aktifitas = $row1['nmr_aktifitas'];
        $sqlcek_detail = "select b.* from table_aktifitas a left join table_aktifitas_detail b on a.nmr_aktifitas = b.nmr_aktifitas where b.tanggal = '".$_POST['tanggal']."' and b.create_by = '".$_POST['create_by']."' and b.nmr_aktifitas not in ('".$new_nmr_aktifitas."') and a.id_company = '" . $_POST['id_company'] . "'";
        // echo $sqlcek_detail;
        $stmt2 = sqlsrv_query($conn_ilv, $sqlcek_detail, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $num_row2 = sqlsrv_num_rows($stmt2);
        $no = 0;
        if ($num_row2 > 0) {
            while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                $no++;
                if ($row2['nmr_aktifitas'] != "") {
                    $sqlup_detail = "update table_aktifitas_detail set nmr_aktifitas = '".$new_nmr_aktifitas."' where nmr_aktifitas = '".$row2['nmr_aktifitas']."'";
                    $stmt3 = sqlsrv_query($conn_ilv, $sqlup_detail, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                
                    if ($stmt3) {
                        $sqlup_aktifitas_agenda = "update table_aktifitas_agenda set nmr_aktifitas = '$new_nmr_aktifitas' where nmr_aktifitas = '".$row2['nmr_aktifitas']."'";
                        $stmt4 = sqlsrv_query($conn_ilv, $sqlup_aktifitas_agenda, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

                        if (!$stmt4) {
                            echo "Error update aktifitas agenda : `$sqlup_aktifitas_agenda`";
                        }

                        $sqldel_aktifitas = "delete from table_aktifitas where tanggal = '".$_POST['tanggal']."' and create_by = '".$_POST['create_by']."' and nmr_aktifitas = '".$row2['nmr_aktifitas']."'";
                        $stmt5 = sqlsrv_query($conn_ilv, $sqldel_aktifitas, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                        if (!$stmt5) {
                            echo "Error clean nmr_aktifitas : `$sqldel_aktifitas`";
                        }

                        if ($stmt3 && $stmt4 && $stmt3 && $num_row2 == $no) {
                            echo "Data berhasil dimerger";
                        }
                    } else {
                        echo "Error update detail : `$sqlup_detail`";
                    }
                }
            }
        } else {
            echo "Agenda di tanggal ini sudah dimerger";
        }
    }
}
