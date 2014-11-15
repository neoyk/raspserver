<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<style>
	table, th, td {border: 1px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
<title>Probe Information</title>
</head>
<body>
<?php
$version = $_REQUEST['version'];
if(!in_array($version, array(4,6)))
	$version = 4;
echo "<h3>Raspberry list - IPv$version ";
if($version==4)
echo "<a href=index.php?version=6>IPv6</a>";
else
echo "<a href=index.php?version=4>IPv4</a>";
echo "</h3>\n";
echo "<table><tr><td>Name&nbsp;</td><td>IP address</td><td>Time&nbsp;</td><td>Bandwidth(bps)&nbsp;</td><td>Latency(ms)&nbsp;</td><td>Lossrate(%)&nbsp;</td></tr>\n";
$category = array('avgbw','avgrtt','avgloss');
$con0 = mysqli_connect("localhost","root","","raspberry");
$con = mysqli_connect("localhost","root","","raspresults");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}
$sql0 = "select code from siteinfo where id >2 order by id ";
$result0 = mysqli_query($con0, $sql0);  
while($row0 = mysqli_fetch_assoc($result0))
{	
	$code = $row0["code"];
	$sql = "select ipv$version from ".$row0["code"]."_address order by time desc limit 1";
	$result = mysqli_query($con,$sql);
	$addr = mysqli_fetch_assoc($result);
	if($version==4)
	{
		$parts = explode("||",$addr["ipv$version"]);
		$address = substr($parts[0],7);
	}
	else
		$address = $addr["ipv$version"];

	echo "<tr><td>$code&nbsp;</td><td><a href=address.php?code=$code>$address</a></td>";
	$timeflag = 1;
	foreach($category as $key)
	{
		$sql = "select $key,time from $key$version where code='$code' and type = 'overall' order by time desc limit 1";
		//echo $sql;
		$result = mysqli_query($con,$sql);
		$value = mysqli_fetch_row($result);
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
		if($timeflag){
			echo "<td>$value[1]</td>";
			$timeflag=0;
		}
		echo "<td>$max$u</td>";
	}
	mysqli_free_result($result);
	echo "</tr>\n";
}
echo "</table>\n";
mysqli_close($con0);
mysqli_close($con);
?>
<br>
Compare:<a href=compare.php?version=<?php echo $version;?>>bandwidth</a>&nbsp;
<a href=compare.php?perf=avgrtt&version=<?php echo $version;?>>latency</a>&nbsp;
<a href=compare.php?perf=avgloss&version=<?php echo $version;?>>lossrate</a>&nbsp;
<br><a href=websites.php>Website list</a>&nbsp;
<br><a href=raspberrypi.img>Image Download</a>&nbsp; Md5sum:cdd8d823149986dffd521d109e5eedba
</body>
</html>
