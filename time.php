<?php
$mac =  $_REQUEST['mac'];
/*echo $code."\n";
echo $version."\n";
 */
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
$sql = "select max(time) from perf_{$mac}_address";
$result = mysql_query($sql,$link);
$entry = mysql_fetch_array($result);
if(!is_null($entry[0]))
	$time = $entry[0];
else
	$time = '2014-10-28 00:00:00';
echo 'address:'.$time."\n";
foreach(array(4,6) as $version)
{
	$sql = "select max(time) from perf_{$mac}_v$version";
	$result = mysql_query($sql,$link);
	$entry = mysql_fetch_array($result);
	if(!is_null($entry[0]))
		$time = $entry[0];
	else
		$time = '2014-10-28 00:00:00';
	echo 'perf_'.$mac.'_v'.$version.':'.$time."\n";
	foreach(array('bw','rtt','loss') as $key)
	{
		$sql = "select max(time) from avg$key$version where mac='$mac'";
		$result = mysql_query($sql,$link);
		$entry = mysql_fetch_array($result);
		if(!is_null($entry[0]))
			$time = $entry[0];
		else
			$time = '2014-10-28 00:00:00';
		echo 'avg'.$key.$version.':'.$time."\n";
	}
}
?>
