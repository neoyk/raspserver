<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Raspberry Registion Page</title>
</head>
<body>
<h2>树莓派命名页面</h2>
<form name = "query" action = "reg.php" method = "get">
<?php
require("function.php");
$link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");  
$code = mysql_escape_string($_GET['code']);
$macfull = strtolower(mysql_escape_string($_GET['mac']));
$mac = mac_short($macfull);
echo "
MAC地址：$macfull<br /><input name = \"mac\" size=20 type = \"hidden\" value=\"$macfull\"/ readonly>
名字：<input name = \"code\" size=20 type = \"text\" value=\"$code\"/><br />
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
			mysql_query("insert into name_history (mac, name, time) values ('$mac', '$code',now())", $link);
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
<br><a href=index.php>Return</a>
</body>
</html>
