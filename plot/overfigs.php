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
$img_height = 110;  //画布高度
$img_width = 185;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 50;  //左侧留下的宽度
$right = 4;  //右侧留下的宽度
$up = 20;  //上面留下的宽度
$down = 20;  //下面留下的宽度
$max = 1;  //最大数据值
$p_x = array();
$p_y = array();
$inx=$_GET['xaxis'];
$type=$_GET['type'];
$code = $_GET['code'];
$in8=$_GET['color'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='avgbw';

if(isset($_GET['table']))
	$table=$_GET['table'];
else
{
	$table = $entry.$version;
}
$xmark = 2 ;
$ymark = 2 ;
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select max(time) from $table where code='$code' and type='$type'", $link);
//$cmd = "select max(time) from $table where ";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
$cmd = "select $entry,time from $table where code='$code' and type='$type'";
if($inx=="Two_days")
	$cmd .= "and time>= now()- interval 48 hour ";
else if($inx=="Month")
	$cmd .= "and time>= now()- interval 720 hour ";
else if($inx=="--OR--")
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
$cmd .= " order by time";
$result = mysql_query($cmd, $link);
$data = array();
while ($row = mysql_fetch_array($result))
{
    array_push($data, $row[0]);
    $time = $row[1];
}
$time = str_replace(array(' ','-',':'),'',$time);
$time = substr($time, 0, 12);
$cou=count($data);

$max = big_ceil(max($data));
if((max($data)-min($data))/max($data)<0.01)
	$min = 2*$data[0]-$max;
else
	$min = big_floor(min($data));

list($max,$scale,$unit) = scale_unit($max);
$min /= $scale;

$jiange=(int)($img_width-$left-$right)/($cou-1);
$image = imagecreate($img_width, $img_height);  //创建画布

for ($i = 0; $i <$cou; $i ++)
{
    array_push($p_x, $left + $i * $jiange);
    array_push($p_y, $up + round(($img_height-$up-$down)*(1-($data[$i]/$scale-$min)/($max-$min))));
}

$white = imagecolorallocate($image,0xFF,0xFF,0xFF);
$black = imagecolorallocate($image,0x00,0x00,0x00);
if($in8=="red")
	$line_color = imagecolorallocate($image,0xFF,0x00,0x00);
else if($in8=="green")
	$line_color = imagecolorallocate($image,0x00,0xFF,0x00);
else if($in8=="purple")
	$line_color = imagecolorallocate($image,0x80,0x00,0x80);
else if($in8=="yellow")
	$line_color = imagecolorallocate($image,0xFF,0xFF,0x80);
else if($in8=="black")
	$line_color = imagecolorallocate($image,0x00,0x00,0x00);
else
	$line_color = imagecolorallocate($image,0x00,0x00,0xFF);

imageline($image, $left, $img_height-$down, $img_width-$right, $img_height-$down, $black);  //画横刻度
imageline($image, $left, $up, $left, $img_height-$down, $black);  //画纵刻度

//echo $border;
imagerectangle($image,$left,$up,$img_width-$right,$img_height-$down,$black);

for ($i=0;$i<=$ymark;$i++)
{
	imageline($image, $left, $up+($img_height-$up-$down)*$i/$ymark, $left+6, $up+($img_height-$up-$down)*$i/$ymark, $black);  //画出y轴i/$ymark刻度的值
	imagestring($image, 4, 20, $up+($img_height-$up-$down)*$i/$ymark-$ymark, round($max*($ymark-$i)/$ymark+$min*$i/$ymark,1), $black);
	//ImageDashedLine($image,$left,$up+($img_height-$up-$down)*$i/$ymark,$img_width-$right,$up+($img_height-$up-$down)*$i/$ymark,$black);//plot dashedline
}

for ($i = 0; $i < $cou - 1; $i ++)
{	
	if($cou<200)
    imageline($image, $p_x[$i], $p_y[$i],$p_x[$i+1],$p_y[$i+1], $line_color);
    imagefilledrectangle($image, $p_x[$i]-1, $p_y[$i]-1,$p_x[$i]+1,$p_y[$i]+1, $line_color);
}

imagefilledrectangle($image, $p_x[$cou-1]-1, $p_y[$cou-1]-1,$p_x[$cou-1]+1,$p_y[$cou-1]+1, $line_color);

//for ($i = 0; $i < $cou; $i ++)
//    imagestring($image, 3, $p_x[$i]+2, $p_y[$i]-12,$data[$i],$black);
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
list($valuenow,$scale,$unit) = scale_unit($data[$cou-1]);
$valuenow = round($valuenow,1);
list($avg,$scale,$uavg) = scale_unit(array_sum($data)/$cou);
$avg = round($avg,1);
imagestring($image, 4, 10, 0,"$initial avg/now:$avg$uavg/$valuenow$unit",$black);
imagestring($image, 4, $left, $img_height-$down,"$cou $time",$black);
if($entry =='avgbw')
imagestringup($image, 4, 0, ($up+$img_height)/1.3,"avgbw($unit"."b/s)",$black);
if($entry=='avgrtt')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"avgrtt(ms)",$black);
if($entry == 'avgloss')
imagestringup($image, 4, 0, ($up+$img_height)/1.4,"avgloss(%)",$black);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
