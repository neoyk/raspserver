<html>
<head>
<meta http-equiv = "Content-Type" content = "text-html; charset = utf-8" />
<title>Measurement Results</title>
</head>
<?php
$perf = $_REQUEST['perf'];
$perflist = array('avgbw','avgrtt','avgloss');
$unit = array("avgbw"=>'bps',"avgrtt"=>'ms','avgloss'=>'%');
if(!in_array($perf, $perflist))
	$perf = 'avgbw';
$version = $_REQUEST['version'];
if(!in_array($version, array(4,6)))
	$version = 4;
require( "perf.php?perf=$perf&version=$version");
/*echo "<frameset cols=\"50%,50%\">\n";
echo "<frame src=\"perf.php?perf=$perf&version=$version\">\n";
if($perf=='avgbw')
echo "<frame src=\"perf.php?perf=avgrtt&version=$version\">\n";
else
echo "<frame src=\"perf.php?perf=avgbw&version=$version\">\n";
 */?>
</html>
