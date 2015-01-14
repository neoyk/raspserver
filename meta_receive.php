<?php
$data =  $_POST['data'];
/*echo $code."\n";
echo $version."\n";
echo $data."\n";
 */
$link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");
$entries = explode('||||',$data);
$count = 0;
//$myfile = fopen("debug", "w");
foreach ($entries as $entry)
{
	//fwrite($myfile, $entry."\n");
	$keyvalue = explode('||',$entry);
	$version = $keyvalue[0];
	$value = $keyvalue[1];
	//fwrite($myfile, $table."\n".$value."\n");
	$columns = explode('|',$value);
	$sql = "update ipv".$version."server set crawl=crawl+$columns[1], error=error+$columns[2], slow=slow+$columns[3] where id=$columns[0]";
	$count += mysql_query($sql,$link);
}
echo $count;
?>
