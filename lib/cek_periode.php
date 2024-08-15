<?php 
session_start();ob_start();
include_once('db_sqlserver_new_nva.php');

    $cek_record = "SELECT * FROM [tbl_transaksi_status_periode_harian] WHERE kode_depo='".$_POST['txt_depo']."' AND tanggal='".$_POST['txt_tanggal']."' AND status='Open' ";
    $stmt_record=sqlsrv_query($conn_new_nva, $cek_record, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $count_status_record=sqlsrv_num_rows($stmt_record);

    if($count_status_record==0){
        $result[] = array("status"=>"E","message_error"=>"","message_success"=>"","other_message"=>"1"); 
    }else{
        $result[] = array("status"=>"S","message_error"=>"","message_success"=>"","other_message"=>""); 
    }
    echo json_encode($result);
?>
