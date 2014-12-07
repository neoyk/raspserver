<?php
function normalize($max)
{
	$u = '';
	if($max>pow(10,6))
	{
	        $scale = pow(2,20);
	        $max = number_format($max/$scale,1);
	        $u = 'M';
	}
	elseif($max>pow(10,3))
	{       //$left=$left+10;
	        $scale = pow(2,10);
	        $max = number_format($max/$scale,1);
	        $u = 'K';
	}
	else
	{
	        $scale = 1;
	        $max = number_format($max/$scale,1);
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
