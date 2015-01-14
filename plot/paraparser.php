<?php
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
error_reporting(E_WARNING);

if($_GET['mac'])
	$macfull = $_GET['mac'];
else
	$macfull = '00:00:00:00:00:00';
$mac = str_replace(":",'',$macfull);
if($_GET['perf'])
	$perf = $_GET['perf'];
else
	$perf = 'avgbw';
$con = mysqli_connect("localhost","root","giat@204","raspresults");
if (mysqli_connect_errno())
{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit(1);
}
$sql = "select code from raspberry.siteinfo where mac='$mac'";
$result2 = mysqli_query($con,$sql);
if( mysqli_num_rows($result2))
{
    $value = mysqli_fetch_row($result2);
    $code = $value[0];
	mysqli_free_result($result2);
}
elseif($_GET['code'])
	$code = $_GET['code'];
else
	$code = 'No name';

$para = array();
if($_GET['bandwidth'])
		array_push($para, 'bandwidth');
if($_GET['latency']) 
		array_push($para, 'latency');
if($_GET['pagesize']) 
		array_push($para, 'pagesize');
if($_GET['lossrate']) 
		array_push($para, 'lossrate');
if($_GET['avgbw']) 
		array_push($para, 'avgbw');
if($_GET['avgrtt']) 
		array_push($para, 'avgrtt');
if($_GET['avgloss']) 
		array_push($para, 'avgloss');
if (in_array($callpage, array('overall.php','all.php')))
{
	if(count($para)==0 ) array_push($para, 'avgbw');
}else
{
	if(count($para)==0 ) array_push($para, 'bandwidth');
}
$k=$_GET['k']; if($k == null)  $k = 2;

$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;

$where=$_GET['where'];

$in=$_GET['xaxis'];	if(strlen($in)==0)	$in="Month";

$in2=$_GET['yaxis'];	if(strlen($in2)==0)	$in2="Auto";

$in3=$_GET['type'];	
if(strlen($in3)==0)	$in3="Domestic Academic";
if($version==6 and $in3=='CERNET')	$in3="Domestic Academic";
$limit=$_GET['limit'];	$limit=intval($limit);	if($limit<=0)	$limit=1;

$in4=$_GET['min'];	$in4=intval($in4);	if(strlen($in4)==0)	$in4=0;

$in5=$_GET['max'];	$in5=intval($in5);	if(strlen($in5)==0)	$in5=0;

$in6=$_GET['xzoom'];	if(strlen($in6)==0 or $in6<0.5 or $in6>3)	$in6=1;

$in7=$_GET['yzoom'];	if(strlen($in7)==0 or $in7<0.5 or $in7>3)	$in7=1;

$in8=$_GET['color'];	if(strlen($in8)==0)	$in8="auto";

date_default_timezone_set('Asia/Chongqing');
$showdate=intval(date("Ymd"));
$correct=1;

$t1=$_GET['time1'];	$t1=intval($t1);	if($t1<20140901 or $t1>$showdate)	$t1=20140901;

$d=$t1%100;
$t=$t1/100;
$m=$t%100;
$y=intval($t/100);
if(!checkdate($m,$d,$y))$correct=0;
//echo "t1=",$t1," d=",$d," m=",$m," y=",$y," correct=",$correct,"<br />";

$t2=$_GET['time2'];	$t2=intval($t2);	if($t2>$showdate or $t2<$t1)	$t2=$showdate;
$d=$t2%100;
$t=$t2/100;
$m=$t%100;
$y=intval($t/100);
if(!checkdate($m,$d,$y))$correct=0;
$ok = $_GET['ok'];
?>
