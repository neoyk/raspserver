<?php
require("../function.php");
require("para.php");
$img_height = 200;  //画布高度
$img_width = 1000;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 70;  //左侧留下的宽度
$right = 40;  //右侧留下的宽度
$up = 10;  //上面留下的宽度
$down = 60;  //下面留下的宽度
$max = 1;  //最大数据值
$iny=$_GET['yaxis'];
$in4=$_GET['min'];
$in5=$_GET['max'];
$in6=$_GET['xzoom'];
$in7=$_GET['yzoom'];
$id0=$_GET['id'];
$short = $_GET['short'];	
if(strlen($short)==0) $short=0;
$margin = 50;
if($short) {$down -= $margin; $img_height -= $margin;}
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='bandwidth';

$img_width=$img_width * $in6;
$img_height=$img_height * $in7;
$xmark = (int)($in6 * 30) ;
$ymark = (int)($in7 * 3) ;
/*
$result0 = mysql_query("select count(*) from raspberry.ipv".$version."server where id=$id0", $link);
$row0 = mysql_fetch_array($result0);
if($row0[0]==0)
{
	$result0 = mysql_query("select min(id) from raspberry.ipv".$version."server", $link);
	$row0 = mysql_fetch_array($result0);
	$id=$row0[0];
}else
 */
$id=$id0;

$cmd = "select $entry,unix_timestamp(time) from $table where id=$id ";
require("data.php");
date_timestamp_set($date, $last_timestamp);
$end_time = date_format($date, 'Y-m-d H:i:s');
date_timestamp_set($date, $first_timestamp);
$start_time = date_format($date, 'Y-m-d H:i:s');

require("image.php");
require("points.php");

if(!$short)
imagestring($image, 4, ($img_width-$right+$jiange)/2-170, $img_height-$down+20,"Time($start_time to $end_time)",$black);
if($entry=='bandwidth' or $entry =='avgbw')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"Bandwidth($unit"."b/s)",$black);
if($entry=='pagesize')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"Pagesize($unit"."B)",$black);
if($entry=='latency' or $entry == 'avgrtt')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"Latency(ms)",$black);
if($entry=='lossrate' or $entry == 'avgloss')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"Lossrate(%)",$black);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
