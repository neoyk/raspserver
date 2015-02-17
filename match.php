<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>User and probe match</title>
</head>
<body>
<p><a href=index.php><img src=img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>User and probe match</span></p>
<hr>
<?php
require("function.php");
$link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");
$ip = getRealIpAddr();
//$ip='202.38.111.1';
if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)==true)
{
	$version = 6;
	echo "Your IPv6 address is $ip, prefix ";
	$ips = explode(':',$ip,5);
	//print_r($ips);
	$prefix = implode(':',array_slice($ips,0,4));
	echo $prefix."<br>\n";
	echo "Probes in the same subnet:<br>\n";
	$sql = "select mac from siteinfo where ipv6 like '$prefix%' order by id";
	//echo $sql;
}
if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)==true)
{
	$version = 4;
	echo "Your IPv4 address is $ip, prefix ";
	$ips = explode('.',$ip);
	//print_r($ips);
	$prefix = implode('.',array_slice($ips,0,3));
	echo $prefix."<br>\n";
	echo "Probes in the same subnet:<br>\n";
	$sql = "select mac from siteinfo where ipv4>=INET_ATON('$prefix.0') and ipv4<=INET_ATON('$prefix.255') order by id";
	//echo $sql;
}
$result = mysql_query($sql, $link);
$count = 0;
while($row = mysql_fetch_array($result))
{       
	$macfull=mac_full($row[0]);
	echo "<a href=http://perf.sasm3.net/raspberry/index.php?mac=$macfull>$macfull</a><br>\n";
	$count += 1;
}       
echo "---Total = $count ---\n<br>";
if($version==6)
	echo "For IPv4 probes please visit <a href=http://115.25.86.4/raspberry/match.php>this page</a>. <br>";
else
	echo "For IPv6 probes please visit <a href=http://[2001:da8:243:8601::864]/raspberry/match.php>this page</a>. ";
echo "<hr>";
include("tail.php");
?>
</body>
</html>
