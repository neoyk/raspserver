<?php
require("../function.php");
require("parafig.php");
$img_height = 110;  //画布高度
$img_width = 187;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 45;  //左侧留下的宽度
$right = 1;  //右侧留下的宽度
$up = 20;  //上面留下的宽度
$down = 20;  //下面留下的宽度
$type=$_GET['type'];
$mac = $_GET['mac'];
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='avgbw';
if(isset($_GET['unify']))
	$unify=intval($_GET['unify']);
else
	$unify=0;

$xmark = 2 ;
$ymark = 2 ;

$cmd = "select $entry, unix_timestamp(time) from $table where mac='$mac' and type='$type' ";
require("data.php");
require("image.php");
require("points.php");
if(strpos($type,' ')===False)
	$initial = $type;
else
{
	$words = explode(" ", $type);
	$initial = "";
	foreach ($words as $w) {
	  $initial .= $w[0];
	}
}
$unit = '';
if(count($data)){
	list($valuenow,$scale,$unit) = scale_unit(end($data));
	$valuenow = round($valuenow,1);
	list($avg,$scale,$uavg) = scale_unit(array_sum($data)/$len);
	$avg = round($avg,1);
	imagestring($image, 4, $left-10, 0,"avg/now:$avg$uavg/$valuenow$unit",$black);
	imagestring($image, 4, $left-2, $img_height-$down,"$len $time",$black);
}
if($entry =='avgbw')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"bw($unit"."b/s)",$black);
if($entry=='avgrtt')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"rtt(ms)",$black);
if($entry == 'avgloss')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"loss(%)",$black);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
