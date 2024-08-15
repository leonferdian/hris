<?php

function getWeeksOfMonth($year,$month)
{
    $currentYear = $year;
    $currentMonth = $month;

    //Substitue year and month
    $time = strtotime("$currentYear-$currentMonth-01");  
    //Got the first week number
    $firstWeek = (int)date("W", $time);

    /*if ($currentMonth == 12)
        $currentYear++;
    else
        $currentMonth++;
	*/

	$dt = new DateTime("$currentYear-$currentMonth-31");
    $time = $dt->format('W');//strtotime("$currentYear-$currentMonth-01") - 86400;
    $lastWeek = $time;//date("W", $time);

    $weekArr = array();
	$weekArr_list = array();

    $j = 1;
    for ($i = $firstWeek; $i <= $lastWeek; $i++) {
        //$weekArr[$i] = 'week ' . $j;
		if($i<=(int)date("W") or $currentYear < date('Y'))
		{
			if($i<=9){
				$weekArr_list['list_week']="0".$i;
			}
			else{
				$weekArr_list['list_week']=$i;
			}
			
			$weekArr_list['number_week']='week ' . $j;
			array_push($weekArr,$weekArr_list);
			$j++;
		}
		
    }
    return $weekArr;
}