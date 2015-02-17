<?php
require 'function.php';
$clientip = getRealIpAddr();
$mac =  $_POST['mac'];
$ipv4 =  $_POST['ipv4'];
$asn4 =  $_POST['asn4'];
$ipv6 =  $_POST['ipv6'];
$asn6 =  $_POST['asn6'];
/*echo $code."\n";
echo $version."\n";
 */
$link = mysql_connect("localhost","root", "giat@204") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
preg_match("/CE:(\d+\.\d+\.\d+\.\d+)/",$ipv4,$matches);
$v4addr = $matches[1];
preg_match("/CE:(.*)\+/",$asn4,$matches);
$asno4 = intval($matches[1]);
$asno6 = intval(substr($asn6,2));
if(filter_var($clientip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) and $v4addr!=$clientip) {
	$ipv4=str_replace($v4addr,$clientip,$ipv4);
	$v4addr = $clientip;
	$client_asn = getASN($clientip);
	$asno4 = $client_asn;
	$asn4=str_replace('NO RECORD','AS'.$client_asn,$asn4);
}
if(filter_var($clientip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) and $ipv6!=$clientip) {
	$ipv6 = $clientip;
	$asn6 = 'AS'.getASN6($clientip);
	$asno6 = intval(substr($asn6,2));
}
if(!filter_var($v4addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
	mysql_query("update raspberry.siteinfo set ipv6='$ipv6', asn6=$asno6 where mac='$mac'",$link);
else
	mysql_query("update raspberry.siteinfo set ipv4=INET_ATON('$v4addr'), ipv6='$ipv6', asn4=$asno4, asn6=$asno6 where mac='$mac'",$link);
if(mysql_affected_rows()==0){
	file_get_contents("http://127.0.0.1/raspberry/autoreg.php?mac=$mac");
	mysql_query("update raspberry.siteinfo set ipv4=INET_ATON('$v4addr'), ipv6='$ipv6', asn4=$asno4, asn6=$asno6 where mac='$mac'",$link);
}
$sql = "insert into perf_{$mac}_address values(null, '$mac','$ipv4', '$asn4', '$ipv6', '$asn6', now())";
echo mysql_query($sql,$link);
?>
