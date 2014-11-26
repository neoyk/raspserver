<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Raspberry Registion Page</title>
</head>
<body>
<h2>树莓派命名页面</h2>
<form name = "query" action = "reg.php" method = "get">
<?php
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");  
$code = mysql_escape_string($_GET['code']);
$desc = mysql_escape_string($_GET['desc']);
$mac = strtolower(mysql_escape_string($_GET['mac']));
echo "
MAC地址：<input name = \"mac\" size=20 type = \"text\" value=\"$mac\"/><br />
名字：<input name = \"code\" size=20 type = \"text\" value=\"$code\"/><br />
描述：<input name = \"desc\" size=60 type = \"text\" value=\"$desc\" /><br />
MAC地址要求纯数字，无空格，必须唯一，如：b827eb6c383f；名字：perf_地区代码_机构简称，描述不超过50个汉字
<br /><input name = \"action\" type = \"submit\" value = \"提交\" />
</form>
";
if(strlen($mac) and strlen($code))
{
	if(!preg_match("/[0-9a-f]{12}/i",$mac))
		echo "MAC地址错误，请重新输入！<br>\n";
	else
	{
		if(strlen($code)>5)
		{
			mysql_query("update siteinfo set code='$code' where mac='$mac'", $link);
			if( mysql_affected_rows($link))
				echo "注册成功！<br>\n";
			else
				echo "注册失败！<br>\n";
		}
		else
			echo "代号不能少于6个字符，请重新输入！<br>\n";
	
	}
}
echo "<p>未命名树莓派列表：</p>\n";
$sql = "select id, mac from siteinfo where id>2 and code is null order by id ";
$result = mysql_query($sql, $link);  
if( mysql_affected_rows($link))
{
	echo "<table><tr><td>id&nbsp;</td><td>MAC地址&nbsp;</td></tr>\n";
	while($row = mysql_fetch_array($result))
	{
		echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><tr>';
	}
}
else
	echo "无";
?>
</table>
</body>
</html>
