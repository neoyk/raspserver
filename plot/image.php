<?php
$image = imagecreate($img_width, $img_height);  //创建画布
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

?>