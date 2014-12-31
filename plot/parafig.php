<?php
$max = 1;  //最大数据值
$p_x = array();
$p_y = array();
if(isset($_GET['color']))
	$color=$_GET['color'];
else
	$color = 'blue';
if(isset($_GET['time1']))
	$t1=$_GET['time1'];
else
	$t1 = '';
if(isset($_GET['time2']))
	$t2=$_GET['time2'];
else
	$t2 = '';
$inx=$_GET['xaxis'];
if(isset($_GET['version']))
	$version = $_GET['version']; 
else
	$version = 4;
if(isset($_GET['unify']))
	$unify = intval($_GET['unify']); 
else
	$unify = 0;
if($version == null or ($version!=4 and $version!=6))  $version = 4;
if(isset($_GET['table']))
	$table=$_GET['table'];
else
	exit();
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 
?>
