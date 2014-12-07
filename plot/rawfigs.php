<?php
require("../function.php");
require("para.php");
$img_height = 110;  //画布高度
$img_width = 195;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 50;  //左侧留下的宽度
$right = 4;  //右侧留下的宽度
$up = 20;  //上面留下的宽度
$down = 20;  //下面留下的宽度
$id0=$_GET['id'];
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='bandwidth';

$xmark = 2 ;
$ymark = 2 ;
/*
$result0 = mysql_query("select count(*) from raspberry.ipv".$version."server where id=$id0", $link);
$row0 = mysql_fetch_array($result0);
#print_r($row[0]);
if($row0[0]==0)
{
	$result0 = mysql_query("select min(id) from raspberry.ipv".$version."server", $link);
	$row0 = mysql_fetch_array($result0);
	$id=$row0[0];
}else
 */
$id=$id0;

$cmd = "select $entry, unix_timestamp(time), asn from $table where id=$id ";
require("data.php");
require("image.php");
require("points.php");

//for ($i = 0; $i < $cou; $i ++)
//    imagestring($image, 3, $p_x[$i]+2, $p_y[$i]-12,$data[$i],$black);
imagestring($image, 4, $left, 0,"$asn id:$id",$black);
imagestring($image, 4, $left, $img_height-$down,"$cou $time",$black);
if($entry=='bandwidth')
imagestringup($image, 4, 0, ($up+$img_height)/1.5,"BW ($unit"."b/s)",$black);
if($entry=='pagesize')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"Pagesize($unit"."B)",$black);
if($entry=='latency')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"Latency(ms)",$black);
if($entry=='lossrate')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"Lossrate(%)",$black);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
