<?php
ini_set('memory_limit', 0);
class Holiday
{
    public static function check_holiday($date)
    {
        include('db_sqlserver_central_input.php');

        $sql = "SELECT [date_day] FROM [db_central_input].[dbo].[tbl_holliday] order by date_day";
        $stmt = sqlsrv_query($conn_ci, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $libur = false;
        $arr_libur = array();
        //month-day
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):
            $arr_libur[] = $row;
        endwhile;

        if (in_array(date("Y-m-d", strtotime($date)), $arr_libur)) $libur = true;

        return $libur;
    }
}