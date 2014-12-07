<?php
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
if(count($p_x)) {
	for ($i=0;$i<=$ymark;$i++)
	{
		imageline($image, $left, $up+($img_height-$up-$down)*$i/$ymark, $left+6, $up+($img_height-$up-$down)*$i/$ymark, $black);  //画出y轴i/$ymark刻度的值
		imagestring($image, 4, 20, $up+($img_height-$up-$down)*$i/$ymark-$ymark, round($max*($ymark-$i)/$ymark+$min*$i/$ymark), $black);
		ImageDashedLine($image,$left,$up+($img_height-$up-$down)*$i/$ymark,$img_width-$right,$up+($img_height-$up-$down)*$i/$ymark,$black);//plot dashedline
	}
	if(strpos($callpage,'s.php')===False){
		$jiange2=($img_width-$left-$right)/$xmark;
		for ($i = 1; $i < $xmark; $i ++)  //输出x轴的刻度
		{
		    //imageline($image, $left+$i*$jiange, $img_height-$down, $left+$i*$jiange, $img_height-$down-6, $black);
		    //imagestring($image, 4, $left+$i*$jiange-8, $img_height-$down+4, $pre[$i], $black);
			ImageDashedLine($image,$left+$i*$jiange2, $img_height-$down, $left+$i*$jiange2, $up, $black);
		}
	}
	$points = count($p_x) - 1;
	for ($i = 0; $i < $points; $i ++)
	{	
		if($p_x[$i+1]-$p_x[$i]<1.5*$jiange)
	    imageline($image, $p_x[$i], $p_y[$i],$p_x[$i+1],$p_y[$i+1], $line_color);
	    imagefilledrectangle($image, $p_x[$i]-1, $p_y[$i]-1,$p_x[$i]+1,$p_y[$i]+1, $line_color);
	}
	imagefilledrectangle($image, $p_x[$points]-1, $p_y[$points]-1,$p_x[$points]+1,$p_y[$points]+1, $line_color);
}
?>
