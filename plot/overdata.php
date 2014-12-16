<?php
$p_x = array();
$p_y = array();
$inx=$_GET['xaxis'];
$type=$_GET['type'];
$mac = $_GET['mac'];
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
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select max(time) from $table where mac='$mac' and type='$type'", $link);
//$cmd = "select max(time) from $table where ";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
include('../function.php');
$macfull = mac_full($mac);
echo "<h3>Raw data:$code, $macfull, $type, $entry, IPv$version</h3>\n";
$cmd = "select $entry,time from $table where mac='$mac' and type='$type'";
if($inx=="Two_days")
	$cmd .= "and time>= now()- interval 48 hour ";
else if($inx=="Month")
	$cmd .= "and time>= now()- interval 720 hour ";
else if($inx=="--OR--")
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
$cmd .= " order by time";
$result = mysql_query($cmd, $link);
while ($row = mysql_fetch_array($result))
{
	echo "$row[1]&nbsp;&nbsp;$row[0]<br>\n";
}

