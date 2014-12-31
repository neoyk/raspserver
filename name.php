<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Change probe name</title>
</head>
<body>
<p><a href=index.php><img src=img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>Change probe name</span></p>
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
$row = mysqli_fetch_assoc($result);
$oldcode=$row['code'];
$code = mysql_escape_string($_GET['code']);
if(strlen($code)<1)
	$code = $oldcode;
echo "
MAC : $macfull<br /><input name = \"mac\" size=20 type = \"hidden\" value=\"$macfull\"/ readonly>\n
Old Name: $oldcode<br>\n
New name: <input name = \"code\" size=20 value=\"$code\"/ ><br />
<br /><input name = \"action\" type = \"submit\" value = \"提交\" />
</form>
";
if(!preg_match("/[0-9a-f]{12}/i",$mac))
	echo "MAC地址错误，请重新输入！<br>\n";
elseif(strlen($code) and strlen($code)<5)
	echo "<b>Name must be longer than 5 characters!</b><br>";
elseif(strlen($code) and $code!=$oldcode)
{
	$sql = "insert into name_history (mac, name, time) values ('$mac', '$code',now())";
	mysqli_query($con, $sql);
	$sql = "update siteinfo set code='$code' where mac='$mac'";
	mysqli_query($con, $sql);
}
echo "<b>Name history:</b><br>\n";
$sql = "select * from name_history where mac='$mac' order by id desc limit 15";
$result=mysqli_query($con, $sql);
echo "<table>";
while($history = mysqli_fetch_assoc($result)){
	$timestr = timeformat($history['time']);
	echo '<tr><td>'.$history['name'].'</td><td>'.$timestr.'</td><td>'.$history['uid']."</td></tr>";
}
mysqli_close($con);
echo "</table>";
echo "<p><a href=index.php>Return</a></p><hr>";
include("tail.php");

?>
</body>
</html>
