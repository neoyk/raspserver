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
foreach(array('perf.sh', 'webcrawl.py', 'ipdetection.py', 'ipv4mnt.py', 'ipv6mnt.py', 'upload.py' ) as $filename)
{
	echo "system wget http://perf.sasm3.net/raspberry/code/$filename -O /root/mnt/$filename\n";
}
?>
