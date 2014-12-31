<?php
//quiet is useless in thie script
if( isset($_REQUEST['quiet']))
	$quiet = intval($_REQUEST['quiet']);
else
	$quiet = 0;
if($quiet==0){
echo "
<html>
<head>
<title>SASM3 - Plot results</title>
<meta http-equiv = \"Content-Type\" content = \"text-html; charset = utf-8\" />
<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"3600\">
<link rel=\"shortcut icon\" href=\"/raspberry/favicon.ico\" type=\"image/x-icon\">
<link rel=\"icon\" href=\"/raspberry/favicon.ico\" type=\"image/x-icon\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
</head>
<body>
";
}
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
if($quiet){
	foreach($category as $genre) {
		if(($cat!='all' and $cat!=$genre) or $genre=='all')
			continue;
		foreach($types as $type) {
			$column = strtoupper($genre).'-'.$maps[$type];
			$urltype = urlencode($genre.'-'.$type);
			echo "<td align=center>$column</td>";
		}
	}
}
else{
	echo "<td><div style=\"width:130px\"><a href=plot.php?unify=$unify&version=$version&cat=$cat&alive=$alive&q=$q&order=name&desc=";
	echo ($order=='name' and $desc=='desc')?"asc":"desc";
	echo ">Name</a></div></td>";
	echo "<td><a href=plot.php?unify=$unify&version=$version&cat=$cat&alive=$alive&q=$q&order=mac&desc=";
	echo ($order=='mac' and $desc=='desc')?"asc":"desc";
	echo ">MAC address</a></td>";
	foreach($category as $genre) {
		if(($cat!='all' and $cat!=$genre) or $genre=='all')
			continue;
		foreach($types as $type) {
			$column = strtoupper($genre).'-'.$maps[$type];
			$urltype = urlencode($genre.'-'.$type);
			echo "<td align=center><a href=plot.php?unify=$unify&version=$version&cat=$cat&alive=$alive&q=$q&order=$urltype&desc=";
			echo ( isset($_REQUEST['order']) and $_REQUEST['order']==$genre.'-'.$type and $desc=='desc')?"asc":"desc";
			echo ">$column</a></td>";
		}
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
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by code ".$desc;
	break;
case 'mac':
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by mac ".$desc;
	break;
case 'time':
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by latest ".$desc;
	break;
case 'compound':
	$sql0 = "select code, b.mac from ( select * from raspresults.current$version where genre='$sgenre' and type='$stype' and time>now()- interval 120 minute) as a right join raspberry.siteinfo as b on a.mac=b.mac where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%' order by vmean ".$desc;
	//echo "\n".$sql0."\n";
	break;
default:
	$sql0 = "select code, mac from siteinfo where latest > $time - interval 20 minute and code like '%$q%' and mac like '%$m%'";
}
$result0 = mysqli_query($con0, $sql0);  
$count = mysqli_affected_rows($con0);
while($row0 = mysqli_fetch_assoc($result0))
{	
	$mac = $row0["mac"];
	$code = $row0["code"];
	$macfull = mac_full($mac);
	if($quiet==0){
		if(strlen($code)<5)
		{	
			$code = 'input name';
			echo "<tr><td ><a href=reg.php?mac=$macfull>$code</a></td><td>$macfull </td>";
		}else
			echo "<tr><td ><a href=control.php?mac=$macfull>$code</a> </td><td>$macfull </td>\n";
	}
	foreach($category as $gen)
	{
		if(($cat!='all' and $cat!=$gen) or $gen=='all')
			continue;
		foreach($types as $type)
		{
			echo "<td>";
			$urltype = urlencode($type);
			echo "<a href=\"plot/overall.php?code=$code&mac=$macfull&type=$urltype&version=$version&avg$gen=1\">";
			if($unify==0)
				echo "<img src=img/$mac/$version-$gen-{$maps["$type"]}.png";
			else
				echo "<img src=img/$mac/$version-$gen-{$maps["$type"]}-unify.png";
			//"/overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&table=avg$gen$version&mac=$mac
			echo " /></a></td>\n";
		}
	}
	echo "</tr>";
}
echo "</table>\n";
if($quiet==0)
echo "---Total = $count ---<br>\n<hr>";
mysqli_close($con0);
mysqli_close($con);
if($quiet==0){
	include("tail.php");
	echo "</body>\n</html>";
}
?>
