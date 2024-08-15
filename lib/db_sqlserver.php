
<?php
/*$serverName = "vpn.padmatirta.info, 1433";  //serverName\instanceName, portNumber (default is 1433)
$connectionInfo = array( "Database"=>"db_pic2", "UID"=>"sa", "PWD"=>"P4dm4t1rt4Gr0up");*/
$serverName = "10.100.100.230, 1433";  //serverName\instanceName, portNumber (default is 1433)
$connectionInfo = array( "Database"=>"db_pic2_replika", "UID"=>"sa", "PWD"=>"P4dm4t1rt4Gr0up");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

/*if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}*/
?>
