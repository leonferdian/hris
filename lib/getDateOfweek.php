<?php

function getDateOfweek($week, $year) {
  
  //$ret['week_start'] = $dto->format('Y-m-d');
  $tglArr = array();
  $tglArr_list = array();
  //$tglArr_list['list_date']=$dto->format('Y-m-d');
  for($i=1;$i<=7;$i++){
		$dto = new DateTime();
		$dto->setISODate($year, $week);
		if($i==1){
			$tglArr_list['list_date']=$dto->format('Y-m-d');
		}
		else{
			$j = $i-1;
			${'plus'.$i} = $dto->modify('+'.$j.' days');			
			$tglArr_list['list_date']=${'plus'.$i}->format('Y-m-d');	
		}		
		array_push($tglArr,$tglArr_list);
  }
  
  
  return $tglArr;
}