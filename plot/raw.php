<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<META HTTP-EQUIV="REFRESH" CONTENT="3600">
<title>Plot Single Web</title>

<link rel="shortcut icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="icon" href="/raspberry/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/raspberry/style.css" />
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
    $("#form").toggle();
  });
});
</script>
</head>

<body>

<?php
require('paraparser.php');
$link = mysql_connect("127.0.0.1", "root", "giat@204") or die('Connecting Failure!');
mysql_select_db("raspresults",$link);

$id0 = $_REQUEST['id'];
$result0 = mysql_query("select count(*) from raspberry.ipv".$version."server where id=$id0", $link);
$row0 = mysql_fetch_array($result0);
if($row0[0]==0)
{
	$result0 = mysql_query("select min(id) from raspberry.ipv".$version."server", $link);
	$row0 = mysql_fetch_array($result0);
	$id=$row0[0];
}else
	$id=$id0;

echo "<p><a href=/raspberry/index.php><img src=/raspberry/img/sasm-logo.jpg height=30></a>&nbsp;
<span class=big>$code, $macfull, IPv$version, $in3, Plot raw data<span></p><hr>\n";
$result2 = mysql_query("select upper(asn),ip from perf_{$mac}_v$version where id=$id order by time desc limit 1", $link);
$row2 = mysql_fetch_array($result2);
$asn = $row2[0];
$ipaddr = $row2[1];
$result2 = mysql_query("select webdomain from raspberry.ipv{$version}server where id=$id", $link);
$row2 = mysql_fetch_array($result2);
$domain = $row2[0];
echo "<h3>$id <a target=blank href=\"http://$domain\">$domain</a>, $asn, $ipaddr</h3>";


if($in=="--OR--" and $correct==0)
	echo "<font color=red>Wrong Data! Please check your input!</font> <br />";
else
	require('plot.php');
//TODO fix the bug in form
//echo "<br><br><button>Click to replot results.</button>\n";
//require("form.php");
echo "<hr\n>";
require('../tail.php');
?>
<br />
</body>
</html>
