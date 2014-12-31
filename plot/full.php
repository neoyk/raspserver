<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>Plot Raw data</title>
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/raspberry/style.css" />

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
include('../function.php');
echo "<p><a href=/raspberry/index.php><img src=/raspberry/img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>$code, $macfull, IPv$version, $where, Plot raw data</span></p><hr>\n";
if(!empty($ok))
{
	if($in=="--OR--" and $correct==0)
		echo "<font color=red>Wrong Data! Please check your input!</font> <br />";
	else
	{	
		$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!');
		mysql_select_db("raspberry",$link);
		mysql_query("flush tables", $link);
		$basedir = dirname(__FILE__);
		//if(!empty($where) and strpos($where,'overall')===false)
		if(!empty($where) and $where != "type='overall'")
			$result1 = mysql_query("select id, webdomain, type from ipv".$version."server where $where order by id",$link);
		else
			$result1 = mysql_query("select id, webdomain, type from ipv".$version."server order by id ",$link);
		while($row1 = mysql_fetch_array($result1))
		{
			$id = $row1[0];
			$domain = $row1[1];
			$type = urlencode($row1[2]);
			//echo "<h3>$id, $domain, $type</h3>";
			require("plot.php");
		}
	}
}
echo "<br><br><button>Click to replot results.</button>\n";
require('form.php');
require('../tail.php');

?>
</body>
</html>
