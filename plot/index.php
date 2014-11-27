<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="1800">
<title>Plot results</title>
<link rel="icon" type="image/png" href="../Raspberry.png">
<style>
	table, th, td {border: 0px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
</head>
<body>
<?php
$version = intval($_REQUEST['version']);//4 or 6
if(!in_array($version, array(4,6)))
	$version = 4;
$alive = intval($_REQUEST['alive']);//4 or 6
if($alive!=0)
	$alive = 1;
echo "<p>Plot measurement results - ";
if($alive)
	echo " alive only, clike to show <a href=index.php?version=$version&alive=0>all</a>";
else
	echo " all probes, clike to show <a href=index.php?version=$version&alive=1>alive ones</a>";
echo " - IPv$version ";
if($version==4)
	echo "<a href=index.php?version=6&alive=$alive>IPv6</a>";
else
	echo "<a href=index.php?version=4&alive=$alive>IPv4</a>";
echo "</p>\n";
$category = array('bw','rtt','loss');
$con0 = mysqli_connect("localhost","root","","raspberry");
$con = mysqli_connect("localhost","root","","raspresults");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}
$types = array('Domestic Academic','Domestic Business','CERNET','CM','CT','CU','International Academic','International Business','overall');
if($version==4)
	$exclude = array('Domestic Academic','Domestic Business','overall');
else
	$exclude = array('Domestic Business','CERNET','CM','CT','CU');

foreach($exclude as $type) {
	if(($key = array_search($type, $types)) !== false) {
	    unset($types[$key]);
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
echo "<table><tr>";
echo "<td><a href=index.php?version=$version&alive=$alive&order=name&desc=";
echo ($order=='name' and $desc=='desc')?"asc":"desc";
echo ">Name</a></td>";
echo '<td>MAC address</td>';
foreach($category as $genre) {
	foreach($types as $type) {
		$column = strtoupper($genre).'-'.$maps[$type];
		$urltype = urlencode($genre.'-'.$type);
		echo "<td align=center><a href=index.php?version=$version&alive=$alive&order=$urltype&desc=";
		echo ($_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
		echo ">$column</a></td>";
	}
}
echo "</tr>";
if(strtolower($desc)!='desc')
	$desc = '';
if($alive)
	$time = 'now()';
else
	$time = "'2014-01-01'";
switch(strtolower($order)){
case 'name':
	$sql0 = "select code, a.mac from siteinfo as a join (select distinct mac from raspresults.current$version where time > $time - interval 2 hour) as b on a.mac=b.mac where id >2 order by code ".$desc;
	break;
case 'time':
	$sql0 = "select code, a.mac from ( select distinct mac, time from raspresults.current$version where time > $time - interval 2 hour) as a join siteinfo as b on a.mac=b.mac order by time ".$desc;
	break;
case 'compound':
	$sql0 = "select code, a.mac from ( select * from raspresults.current$version where genre='$gen' and type='$typ' and time > $time - interval 2 hour) as a join raspberry.siteinfo as b on a.mac=b.mac order by vmean ".$desc;
	//echo "\n".$sql0."\n";
	break;
default:
	$sql0 = "select code, a.mac from siteinfo as a join (select distinct mac from raspresults.current$version where time > $time - interval 2 hour) as b on a.mac=b.mac where id >2".$desc;
}
$result0 = mysqli_query($con0, $sql0);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$code = $row0["code"];
	if(strlen($code)<5)
	{	
		$code = 'input name';
		echo "<tr><td><a href=../reg.php?mac=$mac>$code</a></td><td>$mac </td>";
	}else
		echo "<tr><td>$code </td><td>$mac </td>\n";
	foreach($category as $gen)
	{
		foreach($types as $type)
		{
			echo "<td>";
			$urltype = urlencode($type);
			echo "<a href=\"overall.php?code=$code&mac=$mac&type=$urltype&version=$version&avg$gen=1\">";
			echo "<img src=overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&time1=$t1&time2=$t2&table=avg$gen$version&mac=$mac /></a>\n";
			echo "</td>\n";
		}
	}
	echo "</tr>";
}
echo "</table>\n";
mysqli_close($con0);
mysqli_close($con);
?>
<!--
<br>
Details: <a href=perf.php?version=<?php echo $version;?>>bandwidth</a>&nbsp;
<a href=perf.php?perf=avgrtt&version=<?php echo $version;?>>latency</a>&nbsp;
<a href=perf.php?perf=avgloss&version=<?php echo $version;?>>lossrate</a>&nbsp;
--!>
</body>
</html>
