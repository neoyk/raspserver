<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>Measurement Results</title>
</head>
<body>
<?php
$perf = $_REQUEST['perf'];
$perflist = array('avgbw','avgrtt','avgloss');
$unit = array("avgbw"=>'bps',"avgrtt"=>'ms','avgloss'=>'%');
if(!in_array($perf, $perflist))
	$perf = 'avgbw';
$version = $_REQUEST['version'];
if(!in_array($version, array(4,6)))
	$version = 4;
echo "<p>Performance - IPv$version $perf ($unit[$perf]) </p>\n";
#TODO sort
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");  
if($version==4)
echo "<table><tr><td>Name&nbsp;</td><td>Dom Ac&nbsp;</td><td>Dom Biz&nbsp;</td><td>China Telecom&nbsp;</td><td>China Unicom&nbsp;</td><td>China Mobile&nbsp;</td><td>Int' Ac&nbsp;</td><td>Int' Biz&nbsp;</td><td>overall</td><td>&nbsp;</td></tr>\n";
else
echo "<table><tr><td>Name&nbsp;</td><td>Dom Ac&nbsp;</td><td>Dom Biz&nbsp;</td><td>Int' Ac&nbsp;</td><td>Int' Biz&nbsp;</td><td>overall</td><td>&nbsp;</td></tr>\n";
$sql = "select code, mac from siteinfo where id >2 order by id ";
$result = mysql_query($sql, $link);  
$con = mysqli_connect("localhost","root","","raspresults");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
exit(1);
}
if($version==4)
$category = array('Domestic Academic','Domestic Business','CT','CU','CM','International Academic','International Business','overall');
else
$category = array('Domestic Academic','Domestic Business','International Academic','International Business','overall');
while($row = mysql_fetch_array($result))
{	
	/*
	$sql = "select ipv4 from ".$row[0]."_address order by time desc limit 1";
	$result2 = mysqli_query($con,$sql);
	$address = mysqli_fetch_row($result2);
	$parts = explode("||",$address[0]);
	$address = substr($parts[0],7);
	mysqli_free_result($result2);
	*/
	echo "<tr><td>$row[0]&nbsp;</td>";
	foreach($category as $key)
	{
		$sql = "select $perf from $perf$version where type = '$key' and mac = '$row[1]' order by time desc limit 1";
		//echo $sql;
		$result2 = mysqli_query($con,$sql);
		if( mysqli_num_rows($result2))
		{
			$value = mysqli_fetch_row($result2);
			$max = $value[0];
			$u = '';
			if($max>pow(10,6))
			{
			        $scale = pow(10,6);
			        $max = round($max/$scale,2);
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
			$urlkey = urlencode($key);
			echo "<td><a href=plot/overall.php?code=$row[0]&mac=".$row[1]."&type=$urlkey&version=$version&$perf=1>$max$u</a>&nbsp;</td>\n";
		}else
			echo "<td></td>";
	}
	echo "<td>&nbsp;<a href=plot/all.php?code=$row[0]&mac=".$row[1]."&version=$version&$perf=1&xaxis=Two_days&ok=plot>Plot_all</td>\n";
	echo "</tr>\n";
}
mysqli_close($con);
mysqli_close($con0);
?>
</table>
</body>
</html>
