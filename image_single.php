<?php
#TODO don't download images from localhost, simply generate them from source code
require("header.php");
foreach($version_array as $version) {
	$types = array('Domestic Academic','Domestic Business','CERNET','CM','CT','CU','International Academic','International Business','overall');
	if($version==6){
		$exclude = array('Domestic Business','CERNET','CM','CT','CU');
		foreach($exclude as $type) {
			if(($key = array_search($type, $types)) !== false) {
		    	unset($types[$key]);
			}
		}
	}
	$mac = $argv[1];
	foreach($category as $gen)
	{
		if($gen=='all') continue;
		foreach($types as $type)
		{
			$urltype = urlencode($type);
			$shorttype = $maps["$type"];
			$data = file_get_contents("http://127.0.0.1/raspberry/plot/overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&table=avg$gen$version&mac=$mac");
			$filename = "/var/www/html/raspberry/img/$mac/$version-$gen-{$maps["$type"]}.png";
			$dirname = dirname($filename);
			if (!is_dir($dirname))
			{
				    mkdir($dirname, 0777, true);
			}
			fwrite(fopen($filename,'w'), $data);
			$data = file_get_contents("http://127.0.0.1/raspberry/plot/overfigs.php?entry=avg$gen&type=$urltype&xaxis=Two_days&table=avg$gen$version&mac=$mac&unify=1");
			$filename = "/var/www/html/raspberry/img/$mac/$version-$gen-{$maps["$type"]}-unify.png";
			fwrite(fopen($filename,'w'), $data);
			//echo $filename."\n";
		}
	}
}
mysqli_close($con0);
mysqli_close($con);
?>
