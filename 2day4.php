<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>All results in 2 days</title>
</head>
<body>
<p><a href=index.php><img src=img/sasm-logo.jpg height=30></a>&nbsp;
<?php
require("function.php");
if( isset($_REQUEST['unify']))
	$unify = intval($_REQUEST['unify']);//0 or 1
else
	$unify = 0;
echo "<span class=big>All results in 2 days, ";
if($unify)
	echo "Plot2, ";
else
	echo "Plot, ";
$con = mysqli_connect("localhost","root","giat@204","raspberry");
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
$code=$row['code'];
echo "
$code, $macfull </span></p><hr>\n
";
if(!preg_match("/[0-9a-f]{12}/i",$mac))
	echo "MAC地址错误，请重新输入！<br>\n";
else
	echo "";
mysqli_close($con);
$version_array = array(4,6);
$category = array('bw','rtt','loss');
$types = array('CERNET','CM','CT','CU','International Academic','International Business','overall');

$maps = array();
$maps['CT'] = 'CT';
$maps['CU'] = 'CU';
$maps['CM'] = 'CM';
$maps['CERNET'] = 'CE';
$maps['overall'] = 'AVE';
$maps['Domestic Academic'] = "CE";
$maps['Domestic Business'] = "DB";
$maps['International Academic'] = "IA";
$maps['International Business'] = "IB";

$exclude = array('CM','CT','CU');
foreach($version_array as $v){
	echo file_get_contents("http://127.0.0.1/raspberry/index.php?quiet=1&version=$v&q=$code&mac=$mac&alive=0");
	echo "<table>\n<tr><td>Category</td>";
	foreach($types as $type) 
		echo "<td align=center>".$maps["$type"]."</td>";
	echo "</tr>\n";
	foreach($category as $gen) {
		if( $gen=='all')
			continue;
		echo "<tr><td>".strtoupper($gen)."</td>";
		foreach($types as $type) {
			echo "<td >";
			if($v==6 and in_array($type,$exclude)) 
				echo "<img src=img/empty.png";
			else{
				$urltype = urlencode($type);
				echo "<a href=\"plot/overall.php?code=$code&mac=$macfull&type=$urltype&version=$v&avg$gen=1\">";
				if($unify==0)
					echo "<img src=img/$mac/$v-$gen-{$maps["$type"]}.png";
				else
					echo "<img src=img/$mac/$v-$gen-{$maps["$type"]}-unify.png";
				echo " /></a>";
			}//"/overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&table=avg$gen$version&mac=$mac
			echo " </td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>";
	//echo file_get_contents("http://127.0.0.1/raspberry/plot.php?unify=$unify&quiet=1&version=$v&q=$code&mac=$mac&alive=0");
}
echo "<p><a href=index.php>Return</a></p><hr>";
include("tail.php");
?>
</body>
</html>
