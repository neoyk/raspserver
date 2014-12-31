<html>
<head>
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>All results in 1 month</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<p><a href=index.php><img src=img/sasm-logo.jpg height=30></a>&nbsp;
<?php
require("function.php");
$con = mysqli_connect("localhost","root","","raspberry");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}

if( isset($_REQUEST['version']))
	$version = intval($_REQUEST['version']);//4 or 6
else
	$version = 4;
$version_array = array(4,6);
if(!in_array($version, $version_array))
	$version = 4;
if( isset($_REQUEST['unify']))
	$unify = intval($_REQUEST['unify']);//0 or 1
else
	$unify = 0;
echo "<span class=big>All IPv$version results in 1 month, ";
if($unify)
	echo "Plot2, ";
else
	echo "Plot, ";
$macfull = strtolower(mysql_escape_string($_GET['mac']));
$mac = mac_short($macfull);
if(!preg_match("/[0-9a-f]{12}/i",$mac)){
	echo "MAC地址错误，请重新输入！<br>\n";
	exit(1);
}
$sql = "select code from siteinfo where mac='$mac'";
$result=mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$code=$row['code'];
echo "
$code, $macfull, ";
mysqli_close($con);
//foreach($version_array as $v){
echo file_get_contents("http://127.0.0.1/raspberry/month_plot.php?unify=$unify&quiet=1&version=$version&q=$code&mac=$mac&alive=0");

echo "<p><a href=index.php>Return</a></p><hr>";
include("tail.php");
?>
</body>
</html>
