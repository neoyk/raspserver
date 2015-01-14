<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Raspberry Registion Page</title>
</head>
<body>
<h2>树莓派注册页面</h2>
<form name = "query" action = "reg.php" method = "get">
代号（英文， 10个字母以内，必须唯一）：<input name = "code" size=10 type = "text" /><br />
描述（英文，200个字母内）：<input name = "desc" size=40 type = "text" />
<input name = "action" type = "submit" value = "提交" />
</form>
<?php
	$code = mysql_escape_string($_GET['code']);
	$desc = mysql_escape_string($_GET['desc']);
    $link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
    $db = mysql_select_db("raspberry");  
    mysql_query("set names utf8", $link);
	if(mysql_query("insert into siteinfo values(null,'".$code."','".$desc."')", $link))
	{
		echo "注册成功！";
		$v4 = mysql_query("create table raspresults.".$code."4 (id int not null, ip varchar(16) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		//echo "create table raspresults.".$code."4 (id int not null, ip varchar(16) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ";
		$v6 = mysql_query("create table raspresults.".$code."6 (id int not null, ip varchar(40) not null, asn varchar(20), webdomain varchar(500) not null,time datetime not null, bandwidth double not null, pagesize double not null, latency float not null, lossrate float not null, maxbw double not null, UNIQUE KEY idx_time (id,time)) ENGINE=MyISAM DEFAULT CHARSET=utf8",$link);
		if ($v4 == false or $v6 == false)
			echo "数据库更新失败，树莓派无法与中心控制服务器通信，请联系管理员";
	}
	else
		echo "注册失败！";
	echo "<p>已有账号：</p>\n<table><tr><td>id&nbsp;</td><td>代号&nbsp;</td><td>描述&nbsp;</td></tr>\n";
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
