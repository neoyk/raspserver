<?php
$mac =  $_POST['mac'];
$dusage =  $_POST['dusage'];
$load5min =  $_POST['load5min'];
if(isset($_POST['version']))
	$version =  $_POST['version'];
else
	$version = '1.0';
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
//$myfile = fopen("debug", "w");
//fwrite($myfile, $entry."\n");
//fwrite($myfile, $table."\n".$value."\n");
$sql = "update siteinfo set latest=now(),description='$load5min|$dusage|$version' where mac='$mac'";
mysqli_query($con, $sql);
$count = mysqli_affected_rows( $con );
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
mysqli_close($con);

?>
