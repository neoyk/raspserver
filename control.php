<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Send command to SASM probes</title>
</head>
<body>
<p><a href=index.php><img src=img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>Send command to a probe</span></p>
<hr>
<form name = "query" action = "" method = "get">
<?php
require("function.php");
$con = mysqli_connect("localhost","root","","raspberry");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}

$macfull = strtolower(mysql_escape_string($_GET['mac']));
$mac = mac_short($macfull);
$sql = "select code from siteinfo where mac='$mac'";
$result=mysqli_query($con, $sql);
$code = mysqli_fetch_assoc($result);
$code=$code['code'];
$start = intval($_GET['startvpn']);
if($start!=1) $start = 0;
$stop = intval($_GET['stopvpn']);
if($stop!=1) $stop = 0;
$test_now = intval($_GET['test_now']);
if($test_now!=1) $test_now = 0;
echo "
Name: $code<br />
MAC : $macfull<br /><input name = \"mac\" size=20 type = \"hidden\" value=\"$macfull\"/ readonly>
Command: <input type=checkbox name=startvpn value=1>Start VPN</input> <input type=checkbox name=stopvpn value=1>Stop VPN</input> <input type=checkbox name=test_now value=1>Test now</input>
<br /><p><input name = \"action\" type = \"submit\" value = \"提交\" /></p>
</form>
";
	if(!preg_match("/[0-9a-f]{12}/i",$mac))
		echo "MAC地址错误，请重新输入！<br>\n";
	else
	{
		$sql = "select test_now from siteinfo where mac='$mac'";
		$result = mysqli_query($con, $sql);  
		$row = mysqli_fetch_assoc($result);
		$value = $row["test_now"];
		if($start and $stop)
			echo "Cannot both start and stop VPN.<br>";
		elseif($start){
			$value = $value | 2;
		}
		elseif($stop){
			$value = $value | 4;
		}
		if($test_now){
			$value = $value ^ 8;
		}
		if($value&2 and $value&4)
			$value -= 6;
		$sql = "update siteinfo set test_now=$value where mac='$mac'";
		$result = mysqli_query($con, $sql);
		echo "<b>Commands waiting to send:</b>\n";
		if($value==0)
			echo "<br>NULL\n";
		if($value&2)
			echo "<br>Start VPN in 5 minutes\n";
		if($value&4)
			echo "<br>Stop VPN in 5 minutes\n";
		if($value&8)
			echo "<br>Start test in 5 minutes\n";
	}
echo "<hr><p><a href=name.php?mac=$macfull>Change Name</a></p>\n";
$version_array = array(4,6);
$time_array = array(2,30);
$count = array();
$liveness = array();
foreach($version_array as $v){
	foreach($time_array as $t){
		$sql = "select count(*) from raspresults.avgbw$v where mac='$mac' and time>=now()-interval $t day and type='International Academic'";
		$result = mysqli_query($con, $sql);  
		$row = mysqli_fetch_array($result);
		$count[$v][$t] = $row[0];
		$liveness[$v][$t] = sprintf("%.1f%%", $count[$v][$t]/(24*$t) * 100);
	}
}
//print_r($count);
echo "<a href=2day4.php?mac=$macfull&unify=0>All results in 2 days</a> (# of tests: {$count[4][2]}/{$count[6][2]} (IPv4/IPv6), liveness: {$liveness[4][2]}/{$liveness[6][2]} (IPv4/IPv6))\n";
echo "<br><a href=2day4.php?mac=$macfull&unify=1>All results in 2 days (Plot2)</a>\n";
echo "<br><a href=month_plot_view.php?mac=$macfull&version=4&unify=0>All IPv4 results in 1 month</a> (# of tests: {$count[4][30]}, liveness: {$liveness[4][30]})\n";
echo "<br><a href=month_plot_view.php?mac=$macfull&version=4&unify=1>All IPv4 results in 1 month (Plot2)</a>\n";
echo "<br><a href=month_plot_view.php?mac=$macfull&version=6&unify=0>All IPv6 results in 1 month</a> (# of tests: {$count[6][30]}, liveness: {$liveness[6][30]})\n";
echo "<br><a href=month_plot_view.php?mac=$macfull&version=6&unify=1>All IPv6 results in 1 month (Plot2)</a>\n";
mysqli_close($con);
echo "<p><a href=index.php>Return</a></p><hr>";
include("tail.php");
?>
</body>
</html>
