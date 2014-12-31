<?php
if( isset($_REQUEST['quiet']))
	$quiet = intval($_REQUEST['quiet']);
else
	$quiet = 0;
if($quiet==0){
echo "
<html>
<head>
<title>SASM3 - probe list</title>
<meta http-equiv = \"Content-Type\" content = \"text-html; charset = utf-8\" />
<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"3600\">
<link rel=\"shortcut icon\" href=\"/raspberry/favicon.ico\" type=\"image/x-icon\">
<link rel=\"icon\" href=\"/raspberry/favicon.ico\" type=\"image/x-icon\">
<style>
.black {color:black;}
.gray  {color:#A9A9A9;}
</style>
</head>
<body>
";
}
require("header.php");
$data = array();
$types = array();
if($quiet)
	$sql = "select * from raspresults.current$version where mac = '$mac' order by type"; 
elseif($cat=='all')
	$sql = "select * from raspresults.current$version order by type"; 
else
	$sql = "select * from raspresults.current$version where genre='$cat' order by type";
$result0 = mysqli_query($con0, $sql);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$gen = $row0["genre"]; //bw rtt loss
	$type = $row0["type"]; //CERNET CT CU CM Int..
	if(!array_key_exists($mac, $data))
	{
		$data[$mac] = array();
		$data[$mac]["time"] = $row0["time"];
	}
	if(!array_key_exists($gen, $data[$mac]))
		$data[$mac][$gen] = array();
	if(!array_key_exists($type, $data[$mac][$gen]))
		$data[$mac][$gen][$type] = array();
	if(!in_array($type, $types))
		array_push($types,$type);
	$data[$mac][$gen][$type]['vmin'] = $row0["vmin"];
	$data[$mac][$gen][$type]['vmax'] = $row0["vmax"];
	$data[$mac][$gen][$type]['vmean'] = $row0["vmean"];
	$data[$mac][$gen][$type]['stdv'] = $row0["stdv"];
}
//print_r($data);
//exit();
if($version==4)
{
	foreach(array('Domestic Academic','Domestic Business') as $type) {
		if(($key = array_search($type, $types)) !== false) {
		    unset($types[$key]);
		}
	}
}
echo "<pre>\n";
if($quiet){
	echo "Public IP";
	if($version == 4 )
		echo sprintf("%-7s", '');
	else
		echo sprintf("%-30s", '');
	if($version == 4 )
		echo sprintf("%-23s", ' Local IP');
	echo " ASN     ";
	echo "Datetime".str_repeat(' ',8);
	foreach($category as $genre) {
		if(($cat!='all' and $cat!=$genre) or $genre=='all')
			continue;
		foreach($types as $type) {
			$column = strtoupper($genre).'-'.$maps[$type];
			$urltype = urlencode($genre.'-'.$type);
			echo str_repeat(' ',11-strlen($column))."$column";
		}
	}
}
else{
	echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=name&desc=";
	echo ($order=='name' and $desc=='desc')?"asc":"desc";
	echo ">Name</a>".str_repeat(' ',12);
	echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=mac&desc=";
	echo ($order=='mac' and $desc=='desc')?"asc":"desc";
	echo ">MAC address</a>".str_repeat(' ',7);
	echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=ip&desc=";
	echo ($order=='ip' and $desc=='desc')?"asc":"desc";
	echo ">Public IP</a>";
	if($version == 4 )
		echo sprintf("%-7s", '');
	else
		echo sprintf("%-30s", '');
	if($version == 4 )
		echo sprintf("%-16s", ' Local IP');
	echo " <a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=asn&desc=";
	echo ($order=='asn' and $desc=='desc')?"asc":"desc";
	echo ">ASN</a>     ";
	echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=time&desc=";
	echo ($order=='time' and $desc=='desc')?"asc":"desc";
	echo ">Datetime</a>".str_repeat(' ',8);
	foreach($category as $genre) {
		if(($cat!='all' and $cat!=$genre) or $genre=='all')
			continue;
		foreach($types as $type) {
			$column = strtoupper($genre).'-'.$maps[$type];
			$urltype = urlencode($genre.'-'.$type);
			echo str_repeat(' ',11-strlen($column))."<a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&order=$urltype&desc=";
			echo (isset($_REQUEST['order']) and $_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
			echo ">$column</a>";
		}
	}
}
if($cat=='all')
	echo "   CPU  Disk  Ver  Probe_time      Hour Second Name";
echo " \n";
if(strtolower($desc)!='desc')
	$desc = '';
if($alive)
	$time = 'now()';
else
	$time = "'2014-01-01'";
switch(strtolower($order)){
case 'name':
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by code ".$desc;
	break;
case 'mac':
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by mac ".$desc;
	break;
case 'ip':
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by ipv$version ".$desc;
	break;
case 'asn':
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by asn$version ".$desc;
	break;
case 'time':
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by latest ".$desc;
	break;
case 'compound':
	$sql0 = "select code, b.mac,description,latest, ipv$version, asn$version from ( select * from raspresults.current$version where genre='$sgenre' and type='$stype' and time>now()- interval 2 day) as a right join raspberry.siteinfo as b on a.mac=b.mac where latest > $time - interval 20 minute and code like '%$q%' and b.mac like '%$m%' order by vmean ".$desc;
	break;
default:
	$sql0 = "select code, mac, description, latest, ipv$version, asn$version from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%'";
}
//echo "\n".$sql0."\n";
$result0 = mysqli_query($con0, $sql0);  
$count = mysqli_affected_rows($con0);
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$macfull = mac_full($mac);
	$code = $row0["code"];
	$data[$mac]["latest"] = $row0["latest"];
	if(time()-strtotime($data[$mac]["latest"])>1200)
		$class = 'gray';
	else
		$class = 'black';
	echo "<span class=$class>";
	if(strpos($row0["description"],'|')!==false)
		list($cpu,$disk, $code_version, $machine_time, $hour, $second) = explode('|',$row0["description"]);
	else{
		$cpu = 'N/A';
		$disk = 'N/A';
		$machine_time = 'N/A';
		$code_version = '1.0';
		$hour = -1;
		$second = -2;
	}
	if($quiet==0){
		if(strlen($code)<5)
		{	
			$code = sprintf("%-15s", 'input name');
			echo "<a href=reg.php?mac=$macfull>$code</a> ";
		}else
			echo "<a href=control.php?mac=$macfull>$code</a>".str_repeat(' ',16-strlen($code));
		echo $macfull." ";
	}
	if($version==4){
		$sql = "select ipv$version, asn$version from perf_{$mac}_address order by time desc limit 1";
		$result = mysqli_query($con,$sql);
		$addr = mysqli_fetch_assoc($result);
		if($result->num_rows === 0)
		{
			$local_if = 'N/A';
			$address = 'N/A';
			$asn = 0;
		}
		else
		{
			if(preg_match("/IF:(\d+\.\d+\.\d+\.\d+)/",$addr["ipv4"],$matches))
				$local_if = $matches[1];
			else
				$local_if = 'N/A';
			$parts = explode("+",$addr["ipv4"]);
			list($isp, $address) = explode(":",$parts[0]);
			$parts = explode("+",$addr["asn4"]);
			list($isp, $asn) = explode(":",$parts[0]);
			$asn = intval(substr($asn,2));
		}
	}
	else{
		$address = $row0["ipv6"];
		$asn = $row0["asn6"];
		//$asn = intval(substr($asn,2));
		
		//echo "update raspberry.siteinfo set ipv6='$address',asn6=$asn where mac='$mac'"; 
		//mysqli_query($con0, "update raspberry.siteinfo set ipv6='$address',asn6=$asn where mac='$mac'");  
	}
	if($asn=='NO RECORD' or $asn==0)
		$asn='N/A';
	mysqli_free_result($result);
	echo "<a href=address.php?mac=$macfull>$address</a> ";
	if($version==4)
	{	
		if($quiet)
			echo  str_repeat(' ',16-strlen($address)).$local_if.str_repeat(' ',23-strlen($local_if));
		else
			echo  str_repeat(' ',16-strlen($address)).$local_if.str_repeat(' ',16-strlen($local_if));
	}
	else
		echo  str_repeat(' ',39-strlen($address));
	echo $asn.str_repeat(' ',8-strlen($asn));
	$timestr = timeformat($data[$mac]["latest"]);
	echo "$timestr </span>";
	if(!array_key_exists("time", $data[$mac]) or strtotime($data[$mac]["latest"])-strtotime($data[$mac]["time"])>7200 or time()-strtotime($data[$mac]["time"])>7200)
		$class = 'gray';
	else
		$class = 'black';
	echo "<span class=$class>";
	foreach($category as $gen)
	{
		if(($cat!='all' and $cat!=$gen) or $gen=='all')
			continue;
		foreach($types as $type)
		{
			if(!array_key_exists("time", $data[$mac]) or strtotime($data[$mac]["latest"])-strtotime($data[$mac]["time"])>86400*2 or time()-strtotime($data[$mac]["time"])>86400*2)
				$vmean='N/A';
			else{
				if($gen=='bw')
					$vmean = normalize($data[$mac][$gen][$type]['vmean']);
				else
					$vmean = number_format($data[$mac][$gen][$type]['vmean'], 1);
			//$stdv = $data[$mac][$gen][$type]['stdv'];
			//$stdv = normalize($stdv);
			}

			echo str_repeat(' ',11-strlen($vmean))."$vmean";
		}
	}
	echo " </span>";
	if($cat=='all'){
		echo '  '.$cpu.'  '.$disk.'  '.$code_version.'  '.$machine_time.'  '.str_repeat(' ',3-strlen($hour)).$hour.' '.str_repeat(' ',6-strlen($second)).$second.' ';
		if(strlen($code)<5)
		{	
			$code = sprintf("%-15s", 'input name');
			echo "<a href=reg.php?mac=$macfull>$code</a> ";
		}else
			echo "<a href=control.php?mac=$macfull&code=$code>$code</a>".str_repeat(' ',16-strlen($code));
	}
	echo "\n";
}
if($quiet==0)
	echo "---Total = $count ---\n";
echo "</pre>\n";
//echo "</pre>\n<a href=plot.php?version=$version&alive=$alive>Plot all</a><br />";
mysqli_close($con0);
mysqli_close($con);
//<br><a href=raspbian.tar.gz>Image Download</a>&nbsp; <a href=rasp_manual.pdf>Manual</a> Md5sum: ca08c77e71cf27a225dad3f3c78f2ffd raspbian.tar.gz
if($quiet==0){
	echo "<hr>";
	include("tail.php");
	echo "</body>\n</html>";
}
?>
