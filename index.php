<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>Raspberry list</title>
<link rel="icon" 
      type="image/png" 
      href="Raspberry.png">
</head>
<body>
<?php
function normalize($max)
{
	$u = '';
	if($max>pow(10,6))
	{
	        $scale = pow(10,6);
	        $max = round($max/$scale,1);
	        $u = 'M';
	}
	elseif($max>pow(10,3))
	{       //$left=$left+10;
	        $scale = pow(10,3);
	        $max = round($max/$scale,1);
	        $u = 'K';
	}
	else
	{
	        $scale = 1;
	        $max = round($max/$scale,1);
	}
	return (string)$max.$u;
}
$version = $_REQUEST['version'];//4 or 6
if(!in_array($version, array(4,6)))
	$version = 4;
echo "<p>Raspberry list - IPv$version ";
if($version==4)
	echo "<a href=index.php?version=6>IPv6</a>";
else
	echo "<a href=index.php?version=4>IPv4</a>";
echo " mean/stdv </p>\n";
echo "<pre>\n";
$category = array('bw','rtt','loss');
$con0 = mysqli_connect("localhost","root","","raspberry");
$con = mysqli_connect("localhost","root","","raspresults");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}
$data = array();
$types = array();
$sql0 = "select * from raspresults.current$version order by type ";
$result0 = mysqli_query($con0, $sql0);  
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
	foreach(array('Domestic Academic','Domestic Business','overall') as $type) {
		if(($key = array_search($type, $types)) !== false) {
		    unset($types[$key]);
		}
	}
}
$maps = array();
$maps['CT'] = 'CT';
$maps['CU'] = 'CU';
$maps['CM'] = 'CM';
$maps['CERNET'] = 'CERNET';
$maps['overall'] = 'overall';
$maps['Domestic Academic'] = "Dom Ac";
$maps['Domestic Business'] = "Dom Biz";
$maps['International Academic'] = "Int' Ac";
$maps['International Business'] = "Int' Biz";
if (isset($_REQUEST['order']))
{
	$order = $_REQUEST['order']; //genre - type
	if(strpos($order,'-')>0){
		list($gen, $typ)= explode('-',$order);
		$order = 'compound';
	}
	$sort = 1;
}
else
	$sort = 0;
$desc = $_REQUEST['desc'];  //boolean
echo "<a href=index.php?version=$version&order=name&desc=";
echo ($order=='name' and $desc=='desc')?"asc":"desc";
echo ">Name</a>".str_repeat(' ',9);
echo sprintf("%-13s", 'MAC address');
if($version == 4 )
	echo sprintf("%-16s", 'IP address');
else
	echo sprintf("%-40s", 'IP address');
echo "<a href=index.php?version=$version&order=time&desc=";
echo ($order=='time' and $desc=='desc')?"asc":"desc";
echo ">Time</a>".str_repeat(' ',17);
foreach($category as $genre) {
	foreach($types as $type) {
		$column = strtoupper($genre).'-'.$maps[$type];
		$urltype = urlencode($genre.'-'.$type);
		echo "<a href=index.php?version=$version&order=$urltype&desc=";
		echo ($_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
		echo ">$column</a>".str_repeat(' ',14-strlen($column));

	}
}
echo "\n";
//TODO sort raspberries
if(strtolower($desc)!='desc')
	$desc = '';
switch(strtolower($order)){
case 'name':
	$sql0 = "select code, mac from siteinfo where id >2 order by code ".$desc;
	break;
case 'time':
	$sql0 = "select code, a.mac from ( select distinct mac, time from raspresults.current$version) as a join siteinfo as b on a.mac=b.mac order by time ".$desc;
	break;
case 'compound':
	$sql0 = "select code, a.mac from ( select * from raspresults.current$version where genre='$gen' and type='$typ') as a join raspberry.siteinfo as b on a.mac=b.mac order by vmean ".$desc;
	//echo "\n".$sql0."\n";
	break;
default:
	$sql0 = "select code, mac from siteinfo where id >2 order by id ";
}
$result0 = mysqli_query($con0, $sql0);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$code = $row0["code"];
	if(strlen($code)<5)
	{	
		$code = sprintf("%-12s", 'input name');
		echo "<a href=reg.php?mac=$mac>$code</a> ";
	}else
	{
		$code = sprintf("%-12s", $code);
		echo "$code ";
	}
	$sql = "select ipv$version from perf_{$mac}_address order by time desc limit 1";
	$result = mysqli_query($con,$sql);
	$addr = mysqli_fetch_assoc($result);
	if($version==4)
	{
		$parts = explode("+",$addr["ipv$version"]);
		$address = substr($parts[0],7);
	}
	else
		$address = $addr["ipv$version"];
	mysqli_free_result($result);
	if($version==4)
		$address = sprintf("%-15s", $address);
	else
		$address = sprintf("%-39s", $address);
	echo "$mac <a href=address.php?mac=$mac>$address</a> ";
	$time = $data[$mac]["time"];
	echo "{$data[$mac]["time"]} ";
	foreach($category as $gen)
	{
		foreach($types as $type)
		{
			$vmean = $data[$mac][$gen][$type]['vmean'];
			$vmean = normalize($vmean);

			$stdv = $data[$mac][$gen][$type]['stdv'];
			$stdv = normalize($stdv);

			$urltyp = urlencode($type);
			echo str_repeat(' ',6-strlen($vmean))."<a href=\"plot/overall.php?code=$code&mac=$mac&type=$urltyp&version=$version&avg$gen=1\">$vmean/$stdv</a>".str_repeat(' ',7-strlen($stdv));
		}
	}
	
	echo "\n";
}
echo "</pre>\n<a href=plot/index.php?version=$version>Plot all</a><br />";
mysqli_close($con0);
mysqli_close($con);
?>
<!--
<br>
Details: <a href=perf.php?version=<?php echo $version;?>>bandwidth</a>&nbsp;
<a href=perf.php?perf=avgrtt&version=<?php echo $version;?>>latency</a>&nbsp;
<a href=perf.php?perf=avgloss&version=<?php echo $version;?>>lossrate</a>&nbsp;
--!>
<a href=websites.php>Website list</a>&nbsp;
<br><a href=raspberry.zip>Image Download</a>&nbsp; Md5sum: 4c3dd9979e2c6ad02591273f6ca236dd
</body>
</html>
