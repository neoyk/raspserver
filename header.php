<?php
date_default_timezone_set('Asia/Chongqing');
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
require("function.php");
if( isset($_REQUEST['version']))
	$version = intval($_REQUEST['version']);//4 or 6
else
	$version = 4;
$version_array = array(4,6);
if(!in_array($version, $version_array))
	$version = 4;
if( isset($_REQUEST['alive']))
	$alive = intval($_REQUEST['alive']);
else
	$alive = 1;
if($alive!=1)
	$alive = 0;
if( isset($_REQUEST['unify']))
	$unify = intval($_REQUEST['unify']);
else
	$unify = 0;
if( isset($_REQUEST['cat']))
	$cat = strval($_REQUEST['cat']);
else
	$cat = 'bw';
$category = array('bw','rtt','loss','all');
if(!in_array($cat,$category) and $cat!='all')
	$cat = $category[0];
if( isset($_REQUEST['quiet'])){
	$quiet = intval($_REQUEST['quiet']);
	$cat = 'all';
}
else{
	$quiet = 0;
}
if( isset($_REQUEST['mac'])){
	$m = mac_short($_REQUEST['mac']); //short 12 digits for index.php to search
	$mac = mac_short($_REQUEST['mac']); //for month_plot.php
}
else{
	$m = '';
	$mac = '';
}
if( isset($_REQUEST['token']))
	$token = strval($_REQUEST['token']);
else
	$token = '';
if(strlen($m)<12 and $token!='perf2015')
{
	echo "<h1>Authorization Required</h1>\n";
	exit(1);
}
$CAT = strtoupper($cat);
if (isset($_REQUEST['q']))
	$q = mysql_escape_string($_REQUEST['q']);
else
	$q = '';
if($quiet==0){
	echo "<p><a href=websites.php><img src=img/sasm-logo.jpg height=30></a>\n";
	echo "probes - ";
	if($alive)
		echo " <b>alive only</b>, <a href=$callpage?version=$version&cat=$cat&alive=0&q=$q&token=$token>all probes</a>";
	else
		echo " <a href=$callpage?version=$version&cat=$cat&alive=1&q=$q&mac=$m&token=$token>alive only</a>, <b>all probe</b>";
	echo " | ";
	if($callpage=='index.php')
		echo " <b>list</b>, <a href=plot.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token>Plot</a>, <a href=plot.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token&unify=1>Plot2</a>";
	elseif($unify==0)
		echo " <a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token>list</a>, <b>Plot</b>, <a href=plot.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token&unify=1>Plot2</a>";
	else
		echo " <a href=index.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token>list</a>, <a href=plot.php?version=$version&cat=$cat&alive=$alive&q=$q&mac=$m&token=$token>Plot</a>, <b>Plot2</b>";
	echo " | ";
	foreach($version_array as $v) {
		foreach ($category as $c) {
			if($cat==$c and $version == $v)
				echo '<b>IPv'.$v.'-'.strtoupper($c).'</b>&nbsp;';
			else
				echo "<a href=$callpage?unify=$unify&version=$v&alive=$alive&cat=$c&q=$q&mac=$m&token=$token>IPv".$v.'-'.strtoupper($c).'</a>&nbsp;';
		}
	}
	echo "</p><hr>\n";
	//echo "| mean value</p>\n";
}
$con0 = mysqli_connect("localhost","root","giat@204","raspberry");
$con = mysqli_connect("localhost","root","giat@204","raspresults");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(1);
}

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

if (isset($_REQUEST['order']))
{
	$order = $_REQUEST['order']; //genre - type
	if(strpos($order,'-')>0){
		list($sgenre, $stype)= explode('-',$order);
		$order = 'compound';
	}
	$sort = 1;
}
else
{
	$order = '';
	$sort = 0;
}
if(isset( $_REQUEST['desc']))
	$desc = $_REQUEST['desc'];
else
	$desc = 'asc';
?>
