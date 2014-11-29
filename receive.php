<?php
$mac =  $_POST['mac'];
$data =  $_POST['data'];
$v4stat =  $_POST['v4stat'];
$v6stat =  $_POST['v6stat'];
/*echo $mac."\n";
echo $version."\n";
echo $data."\n";
 */
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
$entries = explode('||||',$data);
$count = 0;
$v4 = 0;
$v6 = 0;
//$myfile = fopen("debug", "w");
foreach ($entries as $entry)
{
	//fwrite($myfile, $entry."\n");
	$keyvalue = explode('||',$entry);
	$table = $keyvalue[0];
	$value = $keyvalue[1];
	if($v4==0 and in_array($table,array('avgbw4','avgrtt4','avgloss4')))
		$v4=substr_count($value,'overall');
	if($v6==0 and in_array($table,array('avgbw6','avgrtt6','avgloss6')))
		$v6=substr_count($value,'overall');
	//fwrite($myfile, $table."\n".$value."\n");
	$columns = explode('|',$value);
	switch($table)
	{
	case 'perf_'.$mac.'_v4':
	case 'perf_'.$mac.'_v6':
		$time = hexdec(substr(md5($mac),25))%24;
		$hour = intval(date('H'));
		if($hour == $time)
			$sql = 'insert ignore into '.$table." values($columns[1],'$columns[2]','$columns[3]','$columns[4]','$columns[5]',$columns[6],$columns[7],$columns[8],$columns[9],$columns[10], $columns[11], '$columns[12]')";
		else
			$sql = 'insert ignore into '.$table." values($columns[1],'$columns[2]','$columns[3]','','$columns[5]',$columns[6],$columns[7],$columns[8],$columns[9],$columns[10], $columns[11], '$columns[12]')";
		break;
	case 'address':
		$sql = "insert ignore into perf_{$mac}_address values($columns[0],'$columns[1]','$columns[2]','$columns[3]','$columns[4]',$columns[5],$columns[6])";
		break;
	default:
		$sql = 'insert ignore into '.$table." values('$columns[0]','$columns[1]',$columns[2],'$columns[3]')";

	}
	$sql = str_replace(',None,',',Null,',$sql);
	$count += mysql_query($sql,$link);
	//fwrite($myfile, $sql."\n");
}
if($v4)
	mysql_query("update raspberry.siteinfo set ipv4count=ipv4count+1,latest=now() where mac='$mac'",$link);
if($v6)
	mysql_query("update raspberry.siteinfo set ipv6count=ipv6count+1,latest=now() where mac='$mac'",$link);
$stat46 = array(4=>$v4stat, 6=>$v6stat);
foreach($stat46 as $key => $stat)
{
	$entries = explode('||||',$stat); # time |||| data
	$rows = explode('||',$entries[1]);
	foreach ($rows as $row)
	{
		$values = explode('|', $row);
		$sql = "replace into current$key values('$mac', '$entries[0]', '$values[0]','$values[1]', $values[2], $values[3], $values[4], $values[5])";
		$count += mysql_query($sql,$link);
	}
}
echo $count;
?>
