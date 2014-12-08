<?php
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");

echo "drop table if exists ipv4server\n";
$sql = 'show create table ipv4server';
$result = mysql_query($sql,$link);
$row = mysql_fetch_array($result);
echo trim(str_replace("\n", '', $row[1]))."\n";

echo "drop table if exists ipv6server\n";
$sql = 'show create table ipv6server';
$result = mysql_query($sql,$link);
$row = mysql_fetch_array($result);
echo trim(str_replace("\n", '', $row[1]))."\n";

$sql = 'select id, ip, webdomain, type from ipv4server order by id';
$result = mysql_query($sql,$link);
while($row = mysql_fetch_array($result))
	echo "insert into ipv4server (id, ip, webdomain, type) values ($row[0],'$row[1]', '$row[2]', '$row[3]')\n";

$sql = 'select id, ip, webdomain, type from ipv6server order by id';
$result = mysql_query($sql,$link);
while($row = mysql_fetch_array($result))
	echo "insert into ipv6server (id, ip, webdomain, type) values ($row[0],'$row[1]', '$row[2]', '$row[3]')\n";

foreach(array(4,6) as $version)
{
	foreach(array('bw','rtt','loss') as $type)
	{
		//echo "drop table if exists avg$type$version\n";
		$sql = "show create table raspresults.avg$type$version";
		$result = mysql_query($sql,$link);
		$row = mysql_fetch_array($result);
		$entry = explode('/*',trim(str_replace("\n", '', $row[1])));
		$create = str_ireplace('create table','create table if not exists',$entry[0]);
		echo $create."\n";
	}
}
echo "CREATE TABLE if not exists `address` (`id` int(11) NOT NULL AUTO_INCREMENT, `mac` char(20) NOT NULL, `ipv4` varchar(200) NOT NULL, `asn4` varchar(200) NOT NULL, `ipv6` varchar(400) NOT NULL, `asn6` varchar(200) NOT NULL, `time` datetime DEFAULT NULL, PRIMARY KEY (`id`))\n";
echo "CREATE TABLE if not exists `web_perf4` (`mac` varchar(20) NOT NULL, `id` int(11) NOT NULL, `ip` varchar(16) NOT NULL, `asn` varchar(20) DEFAULT NULL, `webdomain` varchar(500) NOT NULL, `time` datetime NOT NULL, `bandwidth` double NOT NULL, `pagesize` double NOT NULL, `latency` float NOT NULL, `lossrate` float DEFAULT NULL, `actual_loss` float DEFAULT NULL, `maxbw` double DEFAULT NULL, `type` varchar(50) DEFAULT NULL, UNIQUE KEY `search` (`id`,`time`,`mac`)) ENGINE=MyISAM DEFAULT CHARSET=utf8\n";
echo "CREATE TABLE if not exists `web_perf6` (`mac` varchar(20) NOT NULL, `id` int(11) NOT NULL, `ip` varchar(40) NOT NULL, `asn` varchar(20) DEFAULT NULL, `webdomain` varchar(500) NOT NULL, `time` datetime NOT NULL, `bandwidth` double NOT NULL, `pagesize` double NOT NULL, `latency` float NOT NULL, `lossrate` float DEFAULT NULL, `actual_loss` float DEFAULT NULL, `maxbw` double DEFAULT NULL, `type` varchar(50) DEFAULT NULL, UNIQUE KEY `search` (`id`,`time`,`mac`)) ENGINE=MyISAM DEFAULT CHARSET=utf8\n";
/*
echo "delete from web_perf4 where time<'2014-10-30'\n";
echo "delete from web_perf6 where time<'2014-10-30'\n";
echo "delete from address where time<'2014-10-30'\n";
echo "delete from avgbw4 where time<'2014-10-30'\n";
echo "delete from avgbw6 where time<'2014-10-30'\n";
echo "delete from avgrtt4 where time<'2014-10-30'\n";
echo "delete from avgrtt6 where time<'2014-10-30'\n";
echo "delete from avgloss4 where time<'2014-10-30'\n";
echo "delete from avgloss6 where time<'2014-10-30'"; # no new line after the last command!

$sql = 'show master status';
$result = mysql_query($sql,$link);
$row = mysql_fetch_array($result);
echo "stop slave\n";
echo "reset slave\n";
echo "change master to MASTER_HOST='perf.sasm3.net',master_user='repl',master_password='perf@CERNET2014',master_log_file='".$row[0]."',master_log_pos=".$row[1]."\n";
echo "start slave\n";
		 */
foreach(array('perf.sh', 'webcrawl.py', 'ipdetection.py', 'ipv4mnt.py', 'ipv6mnt.py', 'upload.py', 'syncweb.py' ) as $filename)
//foreach(array('ipdetection.py','webcrawl.py','ipv4' ) as $filename)
{
	$md5sum = md5_file("/var/www/html/raspberry/code/$filename");
	echo "system if [ `/usr/bin/md5sum /root/mnt/$filename|cut -d' ' -f1` != '$md5sum' ] ; then /usr/bin/wget http://perf.sasm3.net/raspberry/code/$filename -O /root/mnt/$filename.new; fi\n";
	echo "system if [ `/usr/bin/md5sum /root/mnt/$filename.new|cut -d' ' -f1` = '$md5sum' ] ; then /bin/mv /root/mnt/$filename.new /root/mnt/$filename; fi\n";
	//echo "system wget http://perf.sasm3.net/raspberry/code/$filename -O /root/mnt/$filename.new\n";
}
?>
