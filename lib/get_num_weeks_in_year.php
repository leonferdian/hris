<?php
    function num_weeks_in_year($year) {
        $year = $_GET['tahun'];
        $daySum=0;
        for($x=1;$x<=12;$x++) 
            $daySum += cal_days_in_month(CAL_GREGORIAN, $x, $year);
        return $daySum/7;
    }
?>