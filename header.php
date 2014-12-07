<?php
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
echo "<p>SASM3 probe list - ";
if($alive)
	echo " <b>alive only</b>, <a href=$callpage?version=$version&alive=0>all probes</a>";
else
	echo " <a href=$callpage?version=$version&alive=1>alive only</a>, <b>all probe</b>";
echo " | ";
if( isset($_REQUEST['cat']))
	$cat = strval($_REQUEST['cat']);
else
	$cat = 'bw';
$category = array('bw','rtt','loss','all');
if(!in_array($cat,$category) and $cat!='all')
	$cat = $category[0];
$CAT = strtoupper($cat);
foreach($version_array as $v) {
	foreach ($category as $c) {
		if($cat==$c and $version == $v)
			echo '<b>IPv'.$v.'-'.strtoupper($c).'</b>&nbsp;';
		else
			echo "<a href=$callpage?version=$v&alive=$alive&cat=$c>IPv".$v.'-'.strtoupper($c).'</a>&nbsp;';
	}
}
//echo "| mean value</p>\n";
$con0 = mysqli_connect("localhost","root","","raspberry");
$con = mysqli_connect("localhost","root","","raspresults");
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
$maps['overall'] = 'all';
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
