<?php
$code =  $_REQUEST['code'];
$version =  $_REQUEST['version'];
/*echo $code."\n";
echo $version."\n";
 */
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
foreach(array(4,6) as $version)
{
	$sql = "select max(time) from $code$version";
	$result = mysql_query($sql,$link);
	$entry = mysql_fetch_array($result);
	if(!is_null($entry[0]))
		$time = $entry[0];
	else
		$time = '2014-10-28 00:00:00';
	echo $code.$version.':'.$time."\n";
	foreach(array('bw','rtt','loss') as $key)
	{
		$sql = "select max(time) from avg$key$version where code='$code'";
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
