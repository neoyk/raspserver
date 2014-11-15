<div id="form" style="display:none">
<?php
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
echo "<form name = plot action = $callpage method = get>\n";
$category = array('Domestic Academic','Domestic Business','CT','CU','CM','International Academic','International Business','overall');
echo "<p><b>Choose site :</b> ";
echo "<select name=code>";
$result3 = mysql_query("select code from raspberry.siteinfo where id>2", $link);
while($row3 = mysql_fetch_array($result3))
{   
	if($code==$row3[0])
		echo "<option selected=selected>$row3[0]</option>";
	else	
		echo "<option>$row3[0]</option>";
}   
echo "</select></p>";
	
echo "<p><b>Choose IP version :</b> ";
echo "<input type=radio name=version value=4 ";
if($version==4) echo "checked";
echo ">4&nbsp;<input type=radio name=version value=6 ";
if($version==6) echo "checked";
echo ">6";
$dbarray = array('CERNET'=>'mnt','Microsoft@Europe'=>'ms1','Microsoft@USA'=>'ms2','Google@USA'=>'google','Brightbox@Britian'=>'brightbox','Aliyun@China'=>'aliyun','Tianyiyun@China'=>'tianyiyun');
if(!in_array($callpage, array('all.php', 'full.php','raw.php')))
{
	echo "<p><b>Choose category :</b> ";
	echo "<select name=type><option selected=selected>$in3</option>\n";
	foreach($category as $key)
	{   
		if($key!=$in3)
		echo "<option>$key</option>";
	}   
	echo "</select>\n";
	echo "</p>\n";
}
elseif($callpage=='full.php')
{
	echo "<p><b>Selection criterion: </b><input name = where type = text size=80 value=\"$where\"></p>\n";
}
elseif($callpage=='raw.php')
{
	echo "<p><b>Input id: </b><input name = id type = text size=20 value=\"$id\"></p>\n";
}
echo "<p><b>Choose parameter: </b>\n";
if (!in_array($callpage,array('overall.php','all.php')))
{
echo "<input type=checkbox name=bandwidth value=1 ";
if(in_array('bandwidth',$para)) echo "checked";
echo ">Bandwidth &nbsp;\n";
echo "<input type=checkbox name=latency value=1 ";
if(in_array('latency',$para)) echo "checked";
echo ">Latency &nbsp;\n";
echo "<input type=checkbox name=pagesize value=1 ";
if(in_array('pagesize',$para)) echo "checked";
echo ">Pagesize &nbsp;\n";
echo "<input type=checkbox name=lossrate value=1 ";
if(in_array('lossrate',$para)) echo "checked";
echo ">Loss rate &nbsp;\n";
}else
{
echo "<input type=checkbox name=avgbw value=1 ";
if(in_array('avgbw',$para)) echo "checked";
echo ">Average bandwidth&nbsp;\n";
echo "<input type=checkbox name=avgrtt value=1 ";
if(in_array('avgrtt',$para)) echo "checked";
echo ">Average Latency &nbsp;\n";
echo "<input type=checkbox name=avgloss value=1 ";
if(in_array('avgloss',$para)) echo "checked";
echo ">Average Loss rate &nbsp;\n";
}/*
if($para=="bandwidth_latency")
	echo "<select name=entry><option selected=selected>Bandwidth_Latency</option><option>Bandwidth</option><option>Latency</option><option>Pagesize</option></select>\n";
elseif($para=="latency")
	echo "<select name=entry><option selected=selected>Latency</option><option>Bandwidth</option><option>Bandwidth_Latency</option><option>Pagesize</option></select>\n";
else if($para=="pagesize")
	echo "<select name=entry><option selected=selected>Pagesize</option><option>Bandwidth</option><option>Latency</option><option>Bandwidth_Latency</option></select>\n";
else
	echo "<select name=entry><option selected=selected>Bandwidth</option><option>Pagesize</option><option>Bandwidth_Latency</option><option>Latency</option></select>\n";
 */
echo "</p>\n";

echo "<p><b>Plot X Range : </b>\n";
if($in=="Month")
	echo "<select name=xaxis><option selected=selected>Month</option><option>Two_days</option><option>Full</option><option>--OR--</option></select>\n";
else if($in=="Full")
	echo "<select name=xaxis><option selected=selected>Full</option><option>Month</option><option>Two_days</option><option>--OR--</option></select>\n";
else if($in=="--OR--")
	echo "<select name=xaxis><option selected=selected>--OR--</option><option>Two_days</option><option>Full</option><option>Month</option></select>\n";
else
{
	echo "<select name=xaxis><option selected=selected>Two_days</option><option>Month</option><option>Full</option><option>--OR--</option></select>\n";
}
if($in=="--OR--")
	echo " --OR-- Choose Start and End Dates: from <input name = time1 type = text size=8 width = 10 value=$t1> to <input name = time2 type = text size=8 width = 10 value=$t2>";
else 
	echo " --OR-- Choose Start and End Dates: from <input name = time1 type = text size=8 width = 10> to <input name = time2 type = text size=8 width = 10>";
 " (eg:20110528 to ".date('Ymd').")</p>\n";

echo "<p><b>Plot Y Range : </b>\n";
if($in2=="Auto")
	echo "<select name=yaxis><option selected=selected>Auto</option><option>--OR--</option></select>\n";
else if($in2=="--OR--")
	echo "<select name=yaxis><option selected=selected>--OR--</option><option>Auto</option></select>\n";
echo " --OR-- Enter Min and Max Y Axis values: from <input name = min type = text size=8 width = 10 \n";
if($in2=="--OR--")
	echo "value=$in4 /> to <input name = max type = text size=8 width = 10 value=$in5 /></p>\n";
else if($in2=="Auto")
	echo "/> to <input name = max type = text size=8 width = 10 /></p>\n";
if(!in_array($callpage,array('full.php','all.php')))
{
	echo "<p><b>Zoom Figure (0.5~3) :</b> Width *  ";
	echo "<input name = xzoom type = text size=8 width = 10 value=$in6>, ";
	echo "Height * ";
	echo "<input name = yzoom type = text size=8 width = 10 value=$in7>. ";
}
echo "\n<b>Plot color: </b>";
if($in8=="red")
	echo "<select name=color><option selected=selected >red</option><option>auto</option><option>green</option><option>blue</option></select>\n";
else if($in8=="green")
	echo "<select name=color><option selected=selected >green</option><option>auto</option><option>red</option><option>blue</option></select>\n";
else if($in8=="blue")
	echo "<select name=color><option selected=selected >blue</option><option>auto</option><option>red</option><option>green</option></select>\n";
else
	echo "<select name=color><option selected=selected >auto</option><option>red</option><option>green</option><option>blue</option></select>\n";
echo "</p>";
echo "<p><input name = ok type = submit value = Plot /></p></form>\n";
?>
</div>
