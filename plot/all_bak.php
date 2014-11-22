<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
    $("#form").toggle();
  });
});
</script>
<style>
	table, th, td {border: 0px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
<title>Web Performance Plot</title>
</head>

<body>

<?php
require('paraparser.php');
$category = array('Domestic Academic','Domestic Business','CERNET','CT','CU','CM','International Academic','International Business','overall');
$v6skip=array('CERNET','CT','CU','CM','Domestic Business');
$v4skip=array('Domestic Academic','Domestic Business','overall');
echo "<h3>Plot all: IPv$version, $code, $mac"; 
foreach($para as $entry)
	echo ", $entry";
echo " </h3>\n";
if(!empty($ok))
{
	if($in=="--OR--" and $correct==0)
		echo "<font color=red>Wrong Data! Please check your input!</font> <br />";
	else
	{	
		echo "<pre>";
		foreach($category as $type)
		{	
			if($version==6 and in_array($type,$v6skip)) continue;
			if($version==4 and in_array($type,$v4skip)) continue;
			$type = urlencode($type);
			require('plot.php');
		}
		echo "</pre>\n";
	}
}
echo "<br><br><button>Click to replot results.</button>\n";
require('form.php');
?>
<div align="center"><p id=b>&copy;1998-<script>clientdate=new Date();document.write(clientdate.getUTCFullYear());</script> <a href="http://www.nic.edu.cn/" target="_blank">CERNIC</a>, <a href="http://www.edu.cn/cernet_fu_wu_1325/index.shtml" target="_blank">CERNET</a>. All rights reserved. China Education and Research Network</p></div>
</body>
