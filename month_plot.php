<?php
require("header.php");
if( isset($_REQUEST['unify']))
	$unify = intval($_REQUEST['unify']);//0 or 1
else
	$unify = 0;
$data = array();
$types = array();
$macfull = mac_full($mac);
$sql = "select * from raspresults.current$version where mac = '$mac' order by type"; 
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
$sql0 = "select code, latest, ipv$version, asn$version from siteinfo where mac='$mac'";
$result0 = mysqli_query($con0, $sql0);  
$count = mysqli_affected_rows($con0);
$row0 = mysqli_fetch_assoc($result0);
$code = $row0["code"];
$data[$mac]["latest"] = $row0["latest"];
if(time()-strtotime($data[$mac]["latest"])>1200)
	$class = 'gray';
else
	$class = 'black';
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
}
if($asn=='NO RECORD' or $asn==0)
	$asn='N/A';
mysqli_free_result($result);
echo "<a href=address.php?mac=$macfull>$address</a>, ";
if($version==4 and $local_if!=$address)
	echo  $local_if.', ';
echo $asn.', ';
$timestr = timeformat($data[$mac]["latest"]);
echo "$timestr </span>\n";
if(!array_key_exists("time", $data[$mac]) or strtotime($data[$mac]["latest"])-strtotime($data[$mac]["time"])>7200 or time()-strtotime($data[$mac]["time"])>7200)
	$class = 'gray';
else
	$class = 'black';
foreach($category as $gen)
{
	$count = 0;
	if(($cat!='all' and $cat!=$gen) or $gen=='all')
		continue;
	echo "<hr><table>\n<tr><td>Type</td><td>Value</td><td>Plot</td></tr>\n";
	foreach($types as $type)
	{
		if($gen=='bw')
			$vmean = normalize($data[$mac][$gen][$type]['vmean']);
		else
			$vmean = number_format($data[$mac][$gen][$type]['vmean'], 1);
		$column = strtoupper($gen).'-'.$maps[$type];
		echo "<tr><td>$column</td><td>$vmean</td>\n";
		$utype = urlencode($type);
		$count += 1;
		if( ($version==4 and in_array($count,array(4,6,7))) or ($version==6 and in_array($count,array(4))) )
			$short = 0;
		else
			$short = 1;
		echo "<td><a href=\"plot/overdata.php?entry=avg$gen&type=$utype&xaxis=Month&table=avg$gen$version&version=$version&mac=$macfull&code=$code\">\n";
		echo "<img src=plot/overfig.php?short=$short&entry=avg$gen&type=$utype&xaxis=Month&yaxis=Auto&xzoom=1&yzoom=1&table=avg$gen$version&mac=$macfull&unify=$unify></a></td></tr>\n";
	}
	echo "</table>\n";
}
mysqli_close($con0);
mysqli_close($con);
?>
