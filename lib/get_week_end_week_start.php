<?php 
    function getStartAndEndDate($week, $year) {
        $dateTime = new DateTime();
        $dateTime->setISODate($year, $week);
        $result['start_date'] = $dateTime->format('d-M-Y');
        $dateTime->modify('+6 days');
        $result['end_date'] = $dateTime->format('d-M-Y');
        return $result;
    }
?>