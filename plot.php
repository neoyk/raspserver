<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="1800">
<title>SASM3 - Plot results</title>
<link rel="icon" type="image/png" href="Raspberry.png">
<style>
	table, th, td {border: 0px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
</head>
<body>
<?php
require("header.php");
$types = array('Domestic Academic','Domestic Business','CERNET','CM','CT','CU','International Academic','International Business','overall');
if($version==4)
	$exclude = array('Domestic Academic','Domestic Business');
else
	$exclude = array('Domestic Business','CERNET','CM','CT','CU');

foreach($exclude as $type) {
	if(($key = array_search($type, $types)) !== false) {
	    unset($types[$key]);
	}
}
echo "<table><tr>";
echo "<td><a href=plot.php?version=$version&cat=$cat&alive=$alive&order=name&desc=";
echo ($order=='name' and $desc=='desc')?"asc":"desc";
echo ">Name</a></td>";
echo '<td>MAC address</td>';
foreach($category as $genre) {
	if(($cat!='all' and $cat!=$genre) or $genre=='all')
		continue;
	foreach($types as $type) {
		$column = strtoupper($genre).'-'.$maps[$type];
		$urltype = urlencode($genre.'-'.$type);
		echo "<td align=center><a href=plot.php?version=$version&cat=$cat&alive=$alive&order=$urltype&desc=";
		echo ( isset($_REQUEST['order']) and $_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
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
	$macfull = mac_full($mac);
	if(strlen($code)<5)
	{	
		$code = 'input name';
		echo "<tr><td><a href=../reg.php?mac=$mac>$code</a></td><td>$macfull </td>";
	}else
		echo "<tr><td>$code </td><td>$macfull </td>\n";
	foreach($category as $gen)
	{
		if(($cat!='all' and $cat!=$gen) or $gen=='all')
			continue;
		foreach($types as $type)
		{
			echo "<td>";
			$urltype = urlencode($type);
			echo "<a href=\"plot/overall.php?code=$code&mac=$mac&type=$urltype&version=$version&avg$gen=1\">";
			echo "<img src=img/$mac/$version-$gen-{$maps["$type"]}.png";
			//"/overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&table=avg$gen$version&mac=$mac
			echo " /></a></td>\n";
		}
	}
	echo "</tr>";
}
echo "</table>\n<hr>";
mysqli_close($con0);
mysqli_close($con);
include("tail.php");
?>
</body>
</html>
