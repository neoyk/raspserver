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
	$sql = "select * from raspresults.current$version order by type "; 
else
	$sql = "select * from raspresults.current$version where genre='$cat' order by type ";
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
	echo sprintf("%-40s", 'Public IP');
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
echo " \n";
if(strtolower($desc)!='desc')
	$desc = '';
if($alive)
	$time = 'now()';
else
	$time = "'2014-01-01'";
switch(strtolower($order)){
case 'name':
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 2 hour order by code ".$desc;
	break;
case 'time':
	$sql0 = "select code, a.mac from ( select distinct mac, time from raspresults.current$version where time > $time - interval 2 hour) as a join siteinfo as b on a.mac=b.mac order by time ".$desc;
	break;
case 'compound':
	$sql0 = "select code, a.mac from ( select * from raspresults.current$version where genre='$sgenre' and type='$stype' and time > $time - interval 2 hour) as a join raspberry.siteinfo as b on a.mac=b.mac order by vmean ".$desc;
	//echo "\n".$sql0."\n";
	break;
default:
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 2 hour";
}
$result0 = mysqli_query($con0, $sql0);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$code = $row0["code"];
	if(strlen($code)<5)
	{	
		$code = sprintf("%-15s", 'input name');
		echo "<a href=reg.php?mac=$mac>$code</a> ";
	}else
	{
		$code = sprintf("%-15s", $code);
		echo "$code ";
	}
	$sql = "select ipv$version, asn$version from perf_{$mac}_address order by time desc limit 1";
	$result = mysqli_query($con,$sql);
	$addr = mysqli_fetch_assoc($result);
	if($version==4)
	{
		if(preg_match("/IF:(\d+\.\d+\.\d+\.\d+)/",$addr["ipv4"],$matches))
			$local_if = $matches[1];
		else
			$local_if = 'N/A';
		$parts = explode("+",$addr["ipv4"]);
		list($prefix, $address) = explode(":",$parts[0]);
		$parts = explode("+",$addr["asn4"]);
		list($prefix, $asn) = explode(":",$parts[0]);
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
	date_default_timezone_set('Asia/Chongqing');
	$timestr = timeformat($data[$mac]["time"]);
	echo "$timestr";
	foreach($category as $gen)
	{
		if(($cat!='all' and $cat!=$gen) or $gen=='all')
			continue;
		foreach($types as $type)
		{
			$vmean = $data[$mac][$gen][$type]['vmean'];
			$vmean = normalize($vmean);

			//$stdv = $data[$mac][$gen][$type]['stdv'];
			//$stdv = normalize($stdv);

			$urltyp = urlencode($type);
			echo str_repeat(' ',11-strlen($vmean))."$vmean";
			//echo str_repeat(' ',6-strlen($vmean))."<a href=\"plot/overall.php?code=$code&mac=$mac&type=$urltyp&version=$version&avg$gen=1\">$vmean/$stdv</a>".str_repeat(' ',7-strlen($stdv));
		}
	}
	
	echo "\n";
}
echo "</pre>\n";
//echo "</pre>\n<a href=plot.php?version=$version&alive=$alive>Plot all</a><br />";
mysqli_close($con0);
mysqli_close($con);
?>
<!--
<br>
Details: <a href=perf.php?version=<?php echo $version;?>>bandwidth</a>&nbsp;
<a href=perf.php?perf=avgrtt&version=<?php echo $version;?>>latency</a>&nbsp;
<a href=perf.php?perf=avgloss&version=<?php echo $version;?>>lossrate</a>&nbsp;
--!>
<hr><a href=websites.php>Website list</a>&nbsp;
<br><a href=raspbian.tar.gz>Image Download</a>&nbsp; <a href=rasp_manual.pdf>Manual</a>
<br>Md5sum:<br>79760fdfc4fda10c4239756671fa4f37 raspbian.img<br>d8def80b589f410e55f31b39990099a9 raspbian.tar.gz
<?php include("tail.php");?>
</body>
</html>
