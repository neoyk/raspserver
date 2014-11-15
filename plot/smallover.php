<?php
function common($in)
{
	$ratio = 1;
	while($in>1000)
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
$img_height = 110;  //画布高度
$img_width = 180;  //画布宽度
$jiange = 0;  //刻度之间的间隔
$left = 50;  //左侧留下的宽度
$right = 4;  //右侧留下的宽度
$up = 20;  //上面留下的宽度
$down = 20;  //下面留下的宽度
$max = 1;  //最大数据值
$p_x = array();
$p_y = array();
$in8=$_GET['color'];
$t1=$_GET['time1'];
$t2=$_GET['time2'];
$id0=$_GET['id'];
$version = $_GET['version']; if($version == null or ($version!=4 and $version!=6))  $version = 4;
$cluster = $_GET['cluster'];
if(!$cluster) $cluster = 0;
$short = $_GET['short'];	
if(strlen($short)==0) $short=0;
$margin = 10;
if($short) {$down -= $margin; $img_height -= $margin;}
if(isset($_GET['entry']))
	$entry=strtolower($_GET['entry']);
else
	$entry='bandwidth';

if(isset($_GET['table']))
	$table=$_GET['table'];
else
{
	if($version==4)
		$table = 'thu4';
	else
		$table = 'thu6';
}
$xmark = 2 ;
$ymark = 2 ;
$link = mysql_connect("127.0.0.1", "root", "") or die('Connecting Failure!'); 
$db = mysql_select_db('raspresults'); 

$result0 = mysql_query("select count(*) from raspberry.ipv".$version."server where id=$id0", $link);
$row0 = mysql_fetch_array($result0);
#print_r($row[0]);
if($row0[0]==0)
{
	$result0 = mysql_query("select min(id) from raspberry.ipv".$version."server", $link);
	$row0 = mysql_fetch_array($result0);
	$id=$row0[0];
}else
	$id=$id0;

$result0 = mysql_query("select max(time) from $table where id=$id", $link);
$cmd = "select max(time) from $table where id=$id";
#file_put_contents('/var/www/html/raspberry/plot/debug',$cmd);
$row0 = mysql_fetch_array($result0);
$date = $row0[0];
if($entry=='bandwidth')
	$cmd = "select 8*$entry,time,asn from $table where id=$id and time> '$date' - interval 2 day order by time";
else	$cmd = "select $entry,time,asn from $table where  id=$id and time> '$date' - interval 2 day order by time";
$result = mysql_query($cmd, $link);
$data = array();
$time = array();
while ($row = mysql_fetch_array($result))
{
    Array_push($data, $row[0]);
    //Array_push($time, $row[1]);
    $time = $row[1];
    $asn = $row[2];
}
$time = substr($time, 0, 16);
$cou=count($data);

$max = big_ceil(max($data));
if((max($data)-min($data))/max($data)<0.01)
	$min = 2*$data[0]-$max;
else
	$min = big_floor(min($data));
$unit = '';
if($max>pow(10,6))
{
	$scale = pow(10,6);
	$max /= $scale;
	$min /= $scale;
	$unit = 'M';
}	
elseif($max>pow(10,3))
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
	imageline($image, $left, $up+($img_height-$up-$down)*$i/$ymark, $left+5, $up+($img_height-$up-$down)*$i/$ymark, $black);  //画出y轴i/$ymark刻度的值
	imagestring($image, 4, 20, $up+($img_height-$up-$down)*$i/$ymark-$ymark, round($max*($ymark-$i)/$ymark+$min*$i/$ymark,1), $black);
	//ImageDashedLine($image,$left,$up+($img_height-$up-$down)*$i/$ymark,$img_width-$right,$up+($img_height-$up-$down)*$i/$ymark,$black);//plot dashedline
}
/*
$jiange2=($img_width-$left-$right)/$xmark;
for ($i = 1; $i < $xmark; $i ++)  //输出x轴的刻度
{
    //imageline($image, $left+$i*$jiange, $img_height-$down, $left+$i*$jiange, $img_height-$down-6, $black);
    //imagestring($image, 4, $left+$i*$jiange-8, $img_height-$down+4, $pre[$i], $black);
	ImageDashedLine($image,$left+$i*$jiange2, $img_height-$down, $left+$i*$jiange2, $up, $black);
}
*/

for ($i = 0; $i < $cou - 1; $i ++)
{	
	if($cou<1000)
    imageline($image, $p_x[$i], $p_y[$i],$p_x[$i+1],$p_y[$i+1], $line_color);
    imagefilledrectangle($image, $p_x[$i]-1, $p_y[$i]-1,$p_x[$i]+1,$p_y[$i]+1, $line_color);
}

imagefilledrectangle($image, $p_x[$cou-1]-1, $p_y[$cou-1]-1,$p_x[$cou-1]+1,$p_y[$cou-1]+1, $line_color);

//for ($i = 0; $i < $cou; $i ++)
//    imagestring($image, 3, $p_x[$i]+2, $p_y[$i]-12,$data[$i],$black);
if(!$short)
imagestring($image, 4, $left, 0,"$asn",$black);
imagestring($image, 4, $left+25, $img_height-$down,"$time",$black);
if($entry=='bandwidth')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.5,"BW ($unit"."b/s)",$black);
if($entry=='pagesize')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.4,"Pagesize($unit"."B)",$black);
if($entry=='latency')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.4,"Latency(ms)",$black);
if($entry=='lossrate')
imagestringup($image, 4, 0, ($up+$img_height+$margin*$short)/1.4,"Lossrate(%)",$black);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
