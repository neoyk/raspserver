<?php
$p_x = array();
$p_y = array();
$id=$_GET['id'];
$inx=$_GET['xaxis'];
$type=$_GET['type'];
$code = $_GET['code'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$version = $_GET['version']; 
if(!in_array($version, array(4,6)))  $version = 4;
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='bandwidth';

if(isset($_GET['table']))
	$table=$_GET['table'];
else
	$table = $code.$version;
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select max(time) from $table where id=$id", $link);
//$cmd = "select max(time) from $table where ";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
$cmd = "select $entry,time,ip from $table where id=$id ";
if($inx=="Two_days")
	$cmd .= "and time>= now()- interval 48 hour ";
else if($inx=="Month")
	$cmd .= "and time>= now()- interval 720 hour ";
else if($inx=="--OR--")
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
$cmd .= " order by time";
$result = mysql_query($cmd, $link);
echo "<h3>Raw data:$code, IPv$version, id: $id, IP address, $entry </h3>\n";
while ($row = mysql_fetch_array($result))
{
	echo "$row[1]&nbsp;&nbsp;$row[2]&nbsp;&nbsp;$row[0]<br>\n";
}

