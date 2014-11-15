<?php
$code =  $_POST['code'];
$data =  $_POST['data'];
/*echo $code."\n";
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
	case $code.'4':
	case $code.'6':
		$sql = 'insert ignore into '.$table." values($columns[0],'$columns[1]','$columns[2]','$columns[3]','$columns[4]',$columns[5],$columns[6],$columns[7],$columns[8],$columns[9], $columns[10], '$columns[11]')";
		break;
	default:
		$sql = 'insert ignore into '.$table." values('$columns[0]','$columns[1]',$columns[2],'$columns[3]')";

	}
	$count += mysql_query($sql,$link);
}
if($v4)
	mysql_query("update raspberry.siteinfo set ipv4count=ipv4count+1,latest=now() where code='$code'",$link);
if($v6)
	mysql_query("update raspberry.siteinfo set ipv6count=ipv6count+1,latest=now() where code='$code'",$link);
echo $count;
?>
