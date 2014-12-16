<?php
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
if($inx=="Two_days") {
	$cou = 48;
	$timestamp = time() - 48*3600;
	$cmd .= "and time>= now()- interval 48 hour ";
}else if($inx=="Month"){
	$cou = 720;
	$timestamp = time() - 720*3600;
	$cmd .= "and time>= now()- interval 720 hour ";
}else if($inx=="--OR--"){
	$timestamp = strtotime($t1);
	$cou = (strtotime($t2)-$timestamp)/3600;
	$cmd .= "and TO_DAYS(time)>=TO_DAYS($t1) and TO_DAYS(time)<=TO_DAYS($t2) ";
}
$cmd .= " order by time";
$data = array();
$missing = array();
$index = 0;
$last_timestamp = $timestamp;
$result = mysql_query($cmd, $link);
while ($row = mysql_fetch_array($result))
{
	if($index==0) $first_timestamp = $row[1];
	if($timestamp>$row[1]) continue;
	while($timestamp+3600<$row[1]){
		$missing[$index] = 1;
		$index += 1;
		$timestamp += 3600;
	}
	$data[$index] = $row[0];
    $last_timestamp = $row[1];
	$index += 1;
	$timestamp += 3600;
}
$len = count($data);
date_default_timezone_set('Asia/Chongqing');
$date = date_create();
date_timestamp_set($date, $last_timestamp);
$time = date_format($date, 'Ymd-His');
if($len>0){
	if($callpage!='overfigs.php') {
		$max = big_ceil(max($data));
		if((max($data)-min($data))/max($data)<0.01)
			$min = 2*$data[0]-$max;
		else
			$min = big_floor(min($data));
	}
	elseif($unify==0){
		$min = 0;
		$cmd = "select max($entry) from $table where mac='$mac' and time>= now()- interval 48 hour";
		$result = mysql_query($cmd, $link);
		$row = mysql_fetch_array($result);
		$max = $row[0];
	}
	else{
		$min = 0;
		switch($entry){
			case 'avgbw':
				$max=5*pow(10,7);
				break;
			case 'avgrtt':
				$max=250;
				break;
			case 'avgloss':
				$max=10;
				break;
		}
	}
	list($max,$scale,$unit) = scale_unit($max);
	$min /= $scale;
	$jiange=(int)($img_width-$left-$right)/($cou-1);
	
	for ($i = 0; $i <$cou; $i ++)
	{
		if(array_key_exists($i,$data)){
		    array_push($p_x, $left + $i * $jiange);
	    	array_push($p_y, $up + round(($img_height-$up-$down)*max(0,1-($data[$i]/$scale-$min)/($max-$min))));
		}
	}
}
?>
