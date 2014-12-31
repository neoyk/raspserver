<?php
$callpage = basename($_SERVER['SCRIPT_FILENAME']);
$len = count($para);
$figidx = 1;
foreach( $para as $entry)
{
	$short = $len == $figidx?0:1;
	if ($callpage=='overall.php')
	{	
		echo "<a href=\"overdata.php?entry=$entry&type=$type&xaxis=$in&time1=$t1&time2=$t2&table=$entry$version&version=$version&mac=$macfull&code=$code\">";
		echo "<img src=overfig.php?short=$short&entry=$entry&type=$type&xaxis=$in&yaxis=$in2&min=$in4&max=$in5&xzoom=$in6&yzoom=$in7&color=$in8&time1=$t1&time2=$t2&table=$entry$version&mac=$macfull&code=$code&unify=0 /></a>\n";
	/*}elseif ($callpage=='all.php')
	{
		echo "<a href=\"overall.php?code=$code&mac=$mac&type=$type&version=$version&avg$gen=1\">";
		echo "<img src=overfigs.php?entry=avg$gen&type=$type&xaxis=Two_days&time1=$t1&time2=$t2&table=avg$gen$version&mac=$mac /></a>\n";
	 */
	}elseif ($callpage=='full.php')
	{
		echo "<a href=\"raw.php?id=$id&code=$code&mac=$macfull&type=$type&version=$version&$entry=1\">";
		echo "<img alt=$domain, src=rawfigs.php?short=$short&entry=$entry&xaxis=$in&yaxis=$in2&min=$in4&max=$in5&xzoom=$in6&yzoom=$in7&color=$in8&time1=$t1&time2=$t2&id=$id&version=$version&table=perf_{$mac}_v$version /></a>\n";
	}elseif ($callpage=='raw.php')
	{
		echo "<a href=\"rawdata.php?id=$id&code=$code&mac=$macfull&type=$type&version=$version&entry=$entry&xaxis=$in&time1=$t1&time2=$t2&table=perf_{$mac}_v$version\">";
		echo "<img src=rawfig.php?short=$short&entry=$entry&xaxis=$in&yaxis=$in2&xzoom=$in6&yzoom=$in7&color=$in8&time1=$t1&time2=$t2&id=$id&version=$version&table=perf_{$mac}_v$version /></a>\n";
	}
	$figidx += 1;
	//echo "\n<br />\n";
		}
?>
