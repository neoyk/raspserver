<html>
<head>
<style>
	table, th, td {border: 1px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
</head>
<body>
<?php
require("function.php");
$macfull =  $_REQUEST['mac'];
$mac = mac_short($macfull);
/*echo $code."\n";
echo $version."\n";
 */
function echoaddr($row)
{
	$macfull = mac_full($row[1]);
	$ipv4 = str_replace('+','<br>',$row[2]);
	$asn4 = str_replace('+','<br>',$row[3]);
	$time = timeformat($row[6]);
    echo "<tr><td>$macfull</td><td>$ipv4</td><td>$asn4</td><td>$row[4]</td><td>$row[5]</td><td>$time</td><tr>";
}
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
$macfull = mac_full($mac);
echo "<p>Current Address of $macfull</p>\n<table ><tr><td>MAC</td><td>IPv4</td><td>ASN4</td><td>IPv6</td><td>ASN6</td><td>Time</td></tr>\n";
$sql = "select * from perf_{$mac}_address order by time desc";
$result = mysql_query($sql, $link);
$row = mysql_fetch_array($result);
echoaddr($row);
echo "</table>\n";
echo "<p>Address Hostory: </p>\n<table ><tr><td>MAC</td><td>IPv4</td><td>ASN4</td><td>IPv6</td><td>ASN6</td><td>Time</td></tr>\n";
while($row = mysql_fetch_array($result))
{       
	echoaddr($row);
}       
echo "</table>\n";
?>
</body>
</html>
