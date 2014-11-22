<?php
function common($in)
{
	$ratio = 1;
	while($in>100)
	{
		$ratio *= 10;
		$in /= 10;
	}
	return array($in, $ratio);
}
function big_ceil($in)
{
	list($in2, $ratio) = common($in);
	return ceil($in2)*$ratio;
}
function big_floor($in)
{
	list($in2, $ratio) = common($in);
	return floor($in2)*$ratio;
}
function scale_unit($max)
{	
	$unit = '';
	if($max>pow(10,6))
	{
		$scale = pow(10,6);
		$max /= $scale;
		$unit = 'm';
	}	
	elseif($max>pow(10,3))
	{	//$left=$left+10;
		$scale = pow(10,3);
		$max /= $scale;
		$unit = 'k';
	}
	else
		$scale = 1;
	return array($max,$scale,$unit);
}
$max = 1;  //最大数据值
$p_x = array();
$p_y = array();
$in8=$_GET['color'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$inx=$_GET['xaxis'];
$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;
if(isset($_GET['table']))
	$table=$_GET['table'];
else
	exit();
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 
?>
