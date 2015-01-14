<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Website Information</title>
</head>
<body>
<?php
$link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
$db = mysql_select_db("raspberry");  
foreach(array(4,6) as $version)
{
	echo "<h3>IPv$version websites</h3>\n";
	echo "<table><tr><td>id&nbsp;</td><td>Domain&nbsp;</td><td>IP</td><td>Category&nbsp;</td></tr>\n";
	$sql = "select id,webdomain,ip,type from ipv$version"."server order by type";
	$result = mysql_query($sql, $link);  
	$category = array('CT','CU','CM');
	while($row = mysql_fetch_array($result))
	{	
		if(strpos($row[1],'/')!==false)
		{	$parts = explode('/',$row[1]);
			$domain = $parts[0];
		}else
			$domain = $row[1];
		if(in_array($row[3],$category))
		echo "<tr><td>$row[0]&nbsp;</td><td>$domain&nbsp;</td><td>$row[2]&nbsp;</td><td>$row[3]&nbsp;</td></tr>\n";
		else
		echo "<tr><td>$row[0]&nbsp;</td><td>$domain&nbsp;</td><td>by DNS&nbsp;</td><td>$row[3]&nbsp;</td></tr>\n";
	}
	echo "</table>";
}
?>
</body>
</html>
