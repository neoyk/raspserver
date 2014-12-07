<?php
date_default_timezone_set('Asia/Chongqing');
$timestamp = time();
$timestr = date('Y:m:d H:i:s',$timestamp);
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");  
$mac = mysql_escape_string($_REQUEST['mac']);
if(!preg_match("/[0-9a-f]{12}/i",$mac))
{
	echo '00000';
}
else
{
	echo '1';
	//echo "insert into siteinfo values(null,'$code','$mac','$desc')";
	if( mysql_query("insert ignore into siteinfo ( mac, latest ) values('$mac' ,'$timestr')", $link))
	{
		echo mysql_affected_rows($link);
		$create4 = mysql_query("create table if not exists raspresults.perf_".$mac."_v4 (id int not null, ip varchar(16) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, actual_loss float not null, maxbw double not null, type varchar(50), UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		$create6 = mysql_query("create table if not exists raspresults.perf_".$mac."_v6 (id int not null, ip varchar(40) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, actual_loss float not null, maxbw double not null, type varchar(50), UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		$address = mysql_query("create table if not exists raspresults.perf_".$mac."_address (id int auto_increment primary key, mac char(20) not null, ipv4 varchar(200) not null,asn4 varchar(100) not null, ipv6 varchar(400) not null, asn6 varchar(100) not null, time datetime not null)",$link);
		if($create4 == 0)	echo "0";	else echo "1";
		if($create6 == 0)	echo "0";	else echo "1";
		if($address == 0)	echo "0";	else echo "1";
	}
	else{
		$result = mysql_query("select count(*) from siteinfo where mac='$mac'", $link);
		$row = mysql_fetch_row($result);
		if($row[0])
		{
			echo "2222";
		}else
			echo "0000";
	}
}
?>
