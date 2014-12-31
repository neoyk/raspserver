<?php
require("function.php");
$mac =  $_POST['mac'];
$dusage =  $_POST['dusage'];
$load5min =  $_POST['load5min'];
$machine_time =  $_POST['time'];
if(isset($_POST['version']))
	$version =  $_POST['version'];
else
	$version = '1.0';
if(isset($_POST['hour']))
	$hour =  $_POST['hour'];
else
	$hour = '-1';
if(isset($_POST['second']))
	$second =  $_POST['second'];
else
	$second = '-2';
/*echo $code."\n";
echo $version."\n";
echo $data."\n";
 */
$con = mysqli_connect("localhost","root","","raspberry");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}
$count = 0;
$sql = "update siteinfo set latest=now(),description='$load5min|$dusage|$version|$machine_time|$hour|$second' where mac='$mac'";
mysqli_query($con, $sql);
$count = mysqli_affected_rows( $con );
if($count==0 )
{
	$count += 16;
	file_get_contents("http://127.0.0.1/raspberry/autoreg.php?mac=$mac");
}
$sql = "select test_now from siteinfo where mac='$mac'";
//echo $sql;
$result = mysqli_query($con, $sql);  
$row = mysqli_fetch_assoc($result);
$count += $row["test_now"];
echo $count;
if($row["test_now"])
{
	$sql = "update siteinfo set test_now=0 where mac='$mac'";
	mysqli_query($con, $sql);
}
$clientip = getRealIpAddr();
if(filter_var($clientip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
	$asn6 = getASN6($clientip);
	$sql = "update siteinfo set ipv6='$clientip',asn6=$asn6 where mac='$mac'";
	mysqli_query($con, $sql);
}
if(filter_var($clientip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
	$asn4 = getASN($clientip);
	$sql = "update siteinfo set ipv4=INET_ATON('$clientip'),asn4=$asn4 where mac='$mac'";
	mysqli_query($con, $sql);
}
//$myfile = fopen("debug", "w");
//fwrite($myfile, $entry."\n");
//fwrite($myfile, $table."\n".$value."\n");
mysqli_close($con);

?>
