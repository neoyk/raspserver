<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>Plot Overall Performance</title>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
    $("#form").toggle();
  });
});
</script>
</head>

<body>

<?php
	
	require('paraparser.php');
	
	echo "<h3>$code, $mac, $in3, IPv$version ";
	foreach($para as $entry)echo ", $entry";
	$map = array('avgbw'=>'bandwidth','avgrtt'=>'latency','avgloss'=>'lossrate');
$where = urlencode("type='$in3'");
echo "&nbsp;&nbsp;<a href=\"full.php?version=$version&mac=$mac&code=$code&xaxis=Two_days&ok=Plot&where=$where";
foreach($para as $entry)
{ $name = $map[$entry];echo "&$name=1";}
echo "\">Individual websites in $in3</a>\n";
	echo "</h3>\n";
	
	if($correct==0)
		echo "<font color=red>Wrong Data! Please check your input!</font> <br />";
	else
	{
		$type = urlencode($in3);
		$table = $perf.$version;
		require('plot.php');
	}
echo "<br><br><button>Click to replot results.</button>\n";
require("form.php");
?>
<br />
<div align="center"><p id=b>&copy;1998-<script>clientdate=new Date();document.write(clientdate.getUTCFullYear());</script> <a href="http://www.nic.edu.cn/" target="_blank">CERNIC</a>, <a href="http://www.edu.cn/cernet_fu_wu_1325/index.shtml" target="_blank">CERNET</a>. All rights reserved. China Education and Research Network</p></div>
</body>
