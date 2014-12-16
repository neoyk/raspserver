<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>SASM3 - probe list</title>
<link rel="icon" 
      type="image/png" 
      href="Raspberry.png">
</head>
<body>
<?php
require("header.php");
$data = array();
$types = array();
if($cat=='all')
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
echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&order=name&desc=";
echo ($order=='name' and $desc=='desc')?"asc":"desc";
echo ">Name</a>".str_repeat(' ',12);
echo sprintf("%-18s", 'MAC address');
if($version == 4 )
	echo sprintf("%-16s", 'Public IP').sprintf("%-16s", ' Local IP');
else
	echo sprintf("%-39s", 'Public IP');
echo " ASN     ";
echo "<a href=index.php?version=$version&cat=$cat&alive=$alive&order=time&desc=";
echo ($order=='time' and $desc=='desc')?"asc":"desc";
echo ">Datetime</a>".str_repeat(' ',7);
foreach($category as $genre) {
	if(($cat!='all' and $cat!=$genre) or $genre=='all')
		continue;
	foreach($types as $type) {
		$column = strtoupper($genre).'-'.$maps[$type];
		$urltype = urlencode($genre.'-'.$type);
		echo str_repeat(' ',11-strlen($column))."<a href=index.php?version=$version&cat=$cat&alive=$alive&order=$urltype&desc=";
		echo (isset($_REQUEST['order']) and $_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
		echo ">$column</a>";

	}
}
if($cat=='all')
	echo "  CPU  Disk  Version";
echo " \n";
if(strtolower($desc)!='desc')
	$desc = '';
if($alive)
	$time = 'now()';
else
	$time = "'2014-01-01'";
switch(strtolower($order)){
case 'name':
	$sql0 = "select code, mac, description, latest from siteinfo where latest > $time - interval 20 minute order by code ".$desc;
	break;
case 'time':
	$sql0 = "select code, mac, description, latest from siteinfo where latest > $time - interval 20 minute order by latest ".$desc;
	break;
case 'compound':
	$sql0 = "select code, b.mac,description,latest from ( select * from raspresults.current$version where genre='$sgenre' and type='$stype' and time>now()- interval 120 minute) as a right join raspberry.siteinfo as b on a.mac=b.mac where latest > $time - interval 20 minute order by vmean ".$desc;
	//echo "\n".$sql0."\n";
	break;
default:
	$sql0 = "select code, mac, description, latest from siteinfo where latest > $time - interval 20 minute";
}
$result0 = mysqli_query($con0, $sql0);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$code = $row0["code"];
	$data[$mac]["latest"] = $row0["latest"];
	if(strpos($row0["description"],'|')!==false)
		list($cpu,$disk, $code_version) = explode('|',$row0["description"]);
	else{
		$cpu = 'N/A';
		$disk = 'N/A';
		$code_version = '1.0';
	}
	if(strlen($code)<5)
	{	
		$code = sprintf("%-15s", 'input name');
		echo "<a href=reg.php?mac=$mac>$code</a> ";
	}else
	{
		//$code = sprintf("%-15s", $code);
		echo "<a href=control.php?mac=$mac&code=$code>$code</a>".str_repeat(' ',16-strlen($code));
	}
	$sql = "select ipv$version, asn$version from perf_{$mac}_address order by time desc limit 1";
	$result = mysqli_query($con,$sql);
	$addr = mysqli_fetch_assoc($result);
	if($result->num_rows === 0)
	{
		$local_if = 'N/A';
		$address = 'N/A';

	}
	elseif($version==4)
	{
		if(preg_match("/IF:(\d+\.\d+\.\d+\.\d+)/",$addr["ipv4"],$matches))
			$local_if = $matches[1];
		else
			$local_if = 'N/A';
		$parts = explode("+",$addr["ipv4"]);
		list($isp, $address) = explode(":",$parts[0]);
		$parts = explode("+",$addr["asn4"]);
		list($isp, $asn) = explode(":",$parts[0]);
		if($asn=='NO RECORD')
			$asn='  N/A';
	}
	else{
		$address = $addr["ipv6"];
		$asn = $addr["asn6"];
	}
	mysqli_free_result($result);
	$macfull = mac_full($mac);
	echo "$macfull <a href=address.php?mac=$mac>$address</a> ";
	if($version==4)
	{	
		echo  str_repeat(' ',16-strlen($address)).$local_if.str_repeat(' ',16-strlen($local_if));
	}
	else
		echo  str_repeat(' ',39-strlen($address));
	echo substr($asn,2).str_repeat(' ',10-strlen($asn));
	$timestr = timeformat($data[$mac]["latest"]);
	echo "$timestr";
	foreach($category as $gen)
	{
		if(($cat!='all' and $cat!=$gen) or $gen=='all')
			continue;
		foreach($types as $type)
		{
			if(!array_key_exists("time", $data[$mac]) or strtotime($data[$mac]["latest"])-strtotime($data[$mac]["time"])>7200 or time()-strtotime($data[$mac]["time"])>7200)
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
	if($cat=='all')
		echo '  '.$cpu.'  '.$disk.'  '.$code_version;
	echo "\n";
}
echo "</pre>\n";
//echo "</pre>\n<a href=plot.php?version=$version&alive=$alive>Plot all</a><br />";
mysqli_close($con0);
mysqli_close($con);
//<br><a href=raspbian.tar.gz>Image Download</a>&nbsp; <a href=rasp_manual.pdf>Manual</a> Md5sum: ca08c77e71cf27a225dad3f3c78f2ffd raspbian.tar.gz
?>
<hr><?php include("tail.php");?>
</body>
</html>
