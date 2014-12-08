<?php
	$addr = 'CE:166.111.133.41+CM:166.111.133.41+CT:166.111.133.41+CU:166.111.133.41+I1:166.111.133.41+I2:166.111.133.41+IF:166.111.133.41';
	echo preg_match("/IF:(\d+\.\d+\.\d+\.\d+)/",$addr,$matches);
	print_r($matches);
	//	echo $time = hexdec(substr(md5('b827ebb0dec6'),25))%24;
	//	echo $hour = intval(date('H'));
?>
