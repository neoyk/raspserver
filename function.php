<?php
function timeformat($time)
{
	date_default_timezone_set('Asia/Chongqing');
	$timestamp = strtotime($time);
	return date('Ymd-His',$timestamp);
}

function normalize($max)
{
	$u = '';
	$scale = pow(10,6);
	if($max>pow(10,3))
	{
	    $max = number_format($max/$scale,4);
	    $u = 'M';
	}/*
	elseif($max>pow(10,3))
	{       
	    $max = number_format($max/$scale,4);
	    $u = 'M';
	}*/
	else
	{
	    $max = number_format($max,1);
	}
	return (string)$max.$u;
}
function mac_full($mac)
{
	return substr($mac,0,2).':'.substr($mac,2,2).':'.substr($mac,4,2).':'.substr($mac,6,2).':'.substr($mac,8,2).':'.substr($mac,10,2);
}
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
?>
