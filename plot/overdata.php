<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/raspberry/style.css" />
<title>Data by type</title>
</head>

<body>

<?php
$p_x = array();
$p_y = array();
$inx=$_GET['xaxis'];
$type=$_GET['type'];
$macfull = $_GET['mac'];
include('../function.php');
$mac = mac_short($macfull);
$code = $_GET['code'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='avgbw';

if(isset($_GET['table']))
	$table=$_GET['table'];
else
	$table = $entry.$version;
$link = mysql_connect("127.0.0.1", "root", "giat@204") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select max(time) from $table where mac='$mac' and type='$type'", $link);
//$cmd = "select max(time) from $table where ";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
echo "<p><a href=/raspberry/index.php><img src=/raspberry/img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>$code, $macfull, $type, IPv$version, $entry</span></p><hr>\n";
$cmd = "select $entry,time from $table where mac='$mac' and type='$type'";
if($inx=="Two_days")
	$cmd .= "and time>= now()- interval 48 hour ";
else if($inx=="Month")
	$cmd .= "and time>= now()- interval 720 hour ";
else if($inx=="--OR--")
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
$cmd .= " order by time";
$result = mysql_query($cmd, $link);
$count = 0;
while ($row = mysql_fetch_array($result))
{
	echo "$row[1]&nbsp;&nbsp;$row[0]<br>\n";
	$count += 1;
}
echo "---Total = $count ---";
echo "<hr>";
require("../tail.php");
?>
<br />
</body>
</html>
