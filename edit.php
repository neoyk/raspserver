<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Raspberry Registion Page</title>
</head>
<body>
<h2>��ݮ��ע��ҳ��</h2>
<form name = "query" action = "reg.php" method = "get">
���ţ�Ӣ�ģ� 10����ĸ���ڣ�����Ψһ����<input name = "code" size=10 type = "text" /><br />
������Ӣ�ģ�200����ĸ�ڣ���<input name = "desc" size=40 type = "text" />
<input name = "action" type = "submit" value = "�ύ" />
</form>
<?php
	$code = mysql_escape_string($_GET['code']);
	$desc = mysql_escape_string($_GET['desc']);
    $link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
    $db = mysql_select_db("raspberry");  
    mysql_query("set names utf8", $link);
	if(mysql_query("insert into siteinfo values(null,'".$code."','".$desc."')", $link))
	{
		echo "ע��ɹ���";
		$v4 = mysql_query("create table raspresults.".$code."4 (id int not null, ip varchar(16) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		//echo "create table raspresults.".$code."4 (id int not null, ip varchar(16) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ";
		$v6 = mysql_query("create table raspresults.".$code."6 (id int not null, ip varchar(40) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		if ($v4 == false or $v6 == false)
			echo "���ݿ����ʧ�ܣ���ݮ���޷������Ŀ��Ʒ�����ͨ�ţ�����ϵ����Ա";
	}
	else
		echo "ע��ʧ�ܣ�";
	echo "<p>�����˺ţ�</p>\n<table><tr><td>id&nbsp;</td><td>����&nbsp;</td><td>����&nbsp;</td></tr>\n";
    $sql = "select * from siteinfo order by id ";
    $result = mysql_query($sql, $link);  
    while($row = mysql_fetch_array($result))
	{
		echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><tr>';
	}
?>
</table>
</body>
</html>
