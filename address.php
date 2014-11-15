<html>
<head>
<style>
	table, th, td {border: 1px solid black; border-collapse: collapse;}
	th,td { padding: 5px}
</style>
</head>
<body>
<?php
$code =  $_REQUEST['code'];
/*echo $code."\n";
echo $version."\n";
 */
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
echo "<p>Address Hostory of $code:</p>\n<table ><tr><td>MAC</td><td>IPv4</td><td>ASN4</td><td>IPv6</td><td>ASN6</td><td>Time</td></tr>\n";
$sql = "select * from ".$code."_address order by time desc";
$result = mysql_query($sql, $link);
while($row = mysql_fetch_array($result))
{       
	$ipv4 = str_replace('||','<br>',$row[2]);
	$asn4 = str_replace('||','<br>',$row[3]);
	$time = $row[6];
        echo "<tr><td>$row[1]</td><td>$ipv4</td><td>$asn4</td><td>$row[4]</td><td>$row[5]</td><td>$time</td><tr>";
}       
?>
</body>
</html>
