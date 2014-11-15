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
$img_height = 200;  //画布高度
$img_width = 1000;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 70;  //左侧留下的宽度
$right = 40;  //右侧留下的宽度
$up = 10;  //上面留下的宽度
$down = 60;  //下面留下的宽度
$max = 1;  //最大数据值
$p_x = array();
$p_y = array();
$inx=$_GET['xaxis'];
$iny=$_GET['yaxis'];
$type=$_GET['type'];
$code = $_GET['code'];
$in4=$_GET['min'];
$in5=$_GET['max'];
$in6=$_GET['xzoom'];
$in7=$_GET['yzoom'];
$in8=$_GET['color'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;
$short = $_GET['short'];	
if(strlen($short)==0) $short=0;
$margin = 50;
if($short) {$down -= $margin; $img_height -= $margin;}
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='avgbw';

if(isset($_GET['table']))
	$table=$_GET['table'];
else
{
	if($version==4)
		$table = 'thu4';
	else
		$table = 'thu6';
}
$img_width=$img_width * $in6;
$img_height=$img_height * $in7;
$xmark = (int)($in6 * 30) ;
$ymark = (int)($in7 * 3) ;
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select max(time) from $table where code='$code' and type='$type'", $link);
//$cmd = "select max(time) from $table where ";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
$cmd = "select $entry,time from $table where code='$code' and type='$type'";
if($inx=="Two_days")
	$cmd .= "and TO_DAYS('$date')-TO_DAYS(time)<=15 and TO_DAYS('$date')-TO_DAYS(time)>=0 ";
else if($inx=="Month")
	$cmd .= "and TO_DAYS('$date')-TO_DAYS(time)<=30 and TO_DAYS('$date')-TO_DAYS(time)>=0 ";
else if($inx=="--OR--")
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
$cmd .= " order by time";
$result = mysql_query($cmd, $link);
$data = array();
$pre1 = array();
while ($row = mysql_fetch_array($result))
{
    Array_push($data, $row[0]);
    Array_push($pre1, $row[1]);
}

$cou=count($data);
$max = max($data);
$min = min($data);
if($iny=="--OR--")
{
	if($in4<$min and $in4>=0)
		$min=$in4;
	if($in5>$max)
		$max=$in5;
}
else 
{	
	if($min==$max) $max=$min+1000;
	$diff= ($max-$min)/6;
	if($diff>$min/2) $diff=$min/2;
	$max = $max+$diff;
	$min = $min-$diff;
	$min = 0;
} 

$max = big_ceil(max($data));
$min = big_floor(min($data));
$unit = '';
if($max>pow(10,7))
{
	$scale = pow(10,6);
	$max /= $scale;
	$min /= $scale;
	$unit = 'M';
}	
elseif($max>pow(10,4))
{	//$left=$left+10;
	$scale = pow(10,3);
	$max /= $scale;
	$min /= $scale;
	$unit = 'K';
}
else
	$scale = 1;
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
	ImageDashedLine($image,$left,$up+($img_height-$up-$down)*$i/$ymark,$img_width-$right,$up+($img_height-$up-$down)*$i/$ymark,$black);//plot dashedline
}

$jiange2=($img_width-$left-$right)/$xmark;
for ($i = 1; $i < $xmark; $i ++)  //输出x轴的刻度
{
    //imageline($image, $left+$i*$jiange, $img_height-$down, $left+$i*$jiange, $img_height-$down-6, $black);
    //imagestring($image, 4, $left+$i*$jiange-8, $img_height-$down+4, $pre[$i], $black);
	ImageDashedLine($image,$left+$i*$jiange2, $img_height-$down, $left+$i*$jiange2, $up, $black);
}


for ($i = 0; $i < $cou - 1; $i ++)
{	
	if($cou<1000)
    imageline($image, $p_x[$i], $p_y[$i],$p_x[$i+1],$p_y[$i+1], $line_color);
    imagefilledrectangle($image, $p_x[$i]-1, $p_y[$i]-1,$p_x[$i]+1,$p_y[$i]+1, $line_color);
}

imagefilledrectangle($image, $p_x[$cou-1]-1, $p_y[$cou-1]-1,$p_x[$cou-1]+1,$p_y[$cou-1]+1, $line_color);

//for ($i = 0; $i < $cou; $i ++)
//    imagestring($image, 3, $p_x[$i]+2, $p_y[$i]-12,$data[$i],$black);
$cou=$cou-1;
if(!$short)
imagestring($image, 4, ($img_width-$right+$jiange)/2-170, $img_height-$down+20,"Time($pre1[0] to $pre1[$cou])",$black);
if($entry=='bandwidth' or $entry =='avgbw')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"Bandwidth($unit"."B/s)",$black);
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
