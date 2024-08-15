<?php 
session_start();ob_start();

if(!isset($_SESSION['depo'])){
    $result[] = array("status"=>"E","message_error"=>"","message_success"=>"","other_message"=>"1"); 
}else{
    if($_SESSION['depo']==0){
        $result[] = array("status"=>"E","message_error"=>"","message_success"=>"","other_message"=>"1"); 
    }else{
        $result[] = array("status"=>"S","message_error"=>"","message_success"=>"","other_message"=>""); 
    }
    // $result[] = array("status"=>"S","message_error"=>"","message_success"=>"","other_message"=>""); 
}
echo json_encode($result);

?>
