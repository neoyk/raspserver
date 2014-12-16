<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Send command to SASM probes</title>
</head>
<body>
<h2>Send command to a probe</h2>
<form name = "query" action = "" method = "get">
<?php
require("function.php");
$con = mysqli_connect("localhost","root","","raspberry");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}

$code = mysql_escape_string($_GET['code']);
$mac = strtolower(mysql_escape_string($_GET['mac']));
$macfull = mac_full($mac);
$start = intval($_GET['startvpn']);
if($start!=1) $start = 0;
$stop = intval($_GET['stopvpn']);
if($stop!=1) $stop = 0;
$test_now = intval($_GET['test_now']);
if($test_now!=1) $test_now = 0;
echo "
Name: $code<br /><input name = \"code\" size=20 type = \"hidden\" value=\"$code\"/ readonly>
MAC : $macfull<br /><input name = \"mac\" size=20 type = \"hidden\" value=\"$mac\"/ readonly>
Command: <input type=checkbox name=startvpn value=1>Start VPN</input> <input type=checkbox name=stopvpn value=1>Stop VPN</input> <input type=checkbox name=test_now value=1>Test now</input>
<br /><input name = \"action\" type = \"submit\" value = \"提交\" />
</form>
<a href=index.php>Return</a><br>
";
if(strlen($mac) and strlen($code))
{
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
			$value = $value | 8;
		}
		if($value&2 and $value&4)
			$value -= 6;
		$sql = "update siteinfo set test_now=$value where mac='$mac'";
		$result = mysqli_query($con, $sql);
		echo "<b>Commands waiting to send:</b>\n";
		if($value&2)
			echo "<br>Start VPN in 5 minutes\n";
		if($value&4)
			echo "<br>Stop VPN in 5 minutes\n";
		if($value&8)
			echo "<br>Start test in 5 minutes\n";
	}
}
mysqli_close($con);
?>
</table>
</body>
</html>
