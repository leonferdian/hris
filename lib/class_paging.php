<?php
class PaggingStandard
{
	// function to check page and postion of the data
	function findPosition($limit)
	{
		
		if (isset($_GET['pagee']))
		{$page = $_GET['pagee']; $position = ($page-1) * $limit;}
		else
		{$position=0; $page=1;}
	
		
	
		return $position;
	}
	
	function findPosition2($limit)
	{
		
	
		if (isset($_GET['page2']))
		{$page = $_GET['page2']; $position = ($page-1) * $limit;}
		else
		{$position=0; $page=1;}
	
		return $position;
	}
	function findPosition3($limit)
	{
		
	
		if (isset($_GET['page2']))
		{$page = $_GET['page2']; $position = ($page-1) * $limit;}
		else
		{$position=0; $page=1;}
	
		return $position;
	}
	// function to count total of pages
	function numberofPages($noofData, $limit)
	{
		$noofPages = ceil($noofData/$limit); return $noofPages;
	}
	function numberofPages2($noofData, $limit)
	{
		$noofPages = ceil($noofData/$limit); return $noofPages;
	}
	function numberofPages4($noofData, $limit)
	{
		$noofPages = ceil($noofData/$limit); return $noofPages;
	}
	// function to show pages link 1,2,3... Next, Prev, First, Last
	
	function navPages($page, $noofPages, $web_link)
	{
		
		$pageLink=($page > 5 ? "  " : " ");
		for($i=$page-5;$i<$page;$i++)
		{
		  if ($i < 1) 
			  continue;
		  $pageLink .= "<li><a href=$web_link&pagee=$i>$i</A></li> ";
		}

		$pageLink .= " <li class='active'><a href='#'>$page</a></li> ";
		for($i=$page+1;$i<($page+6);$i++)
		{
		  if ($i > $noofPages) 
			  break;
		  $pageLink .= "<li><a href=$web_link&pagee=$i>$i</A></li> ";
		}

		$pageLink .= ($page+2<$noofPages ? "  " : " ");
		return $pageLink;
		
	}
	function navPages2($page, $noofPages, $web_link)
	{
		
			$pageLink=($page > 5 ? "  " : " ");
			for($i=$page-5;$i<$page;$i++)
			{
			  if ($i < 1) 
				  continue;
			  $pageLink .= "<li><a href=$web_link&page2=$i>$i</A></li> ";
			}

			$pageLink .= " <li class='active'><a href='#'>$page</a></li> ";
			for($i=$page+1;$i<($page+6);$i++)
			{
			  if ($i > $noofPages) 
				  break;
			  $pageLink .= "<li><a href=$web_link&page2=$i>$i</A></li> ";
			}

			$pageLink .= ($page+2<$noofPages ? "  " : " ");
			return $pageLink;
		
	}
}
?>
