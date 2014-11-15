<?php
$code =  $_POST['code'];
$mac =  $_POST['mac'];
$ipv4 =  $_POST['ipv4'];
$asn4 =  $_POST['asn4'];
$ipv6 =  $_POST['ipv6'];
$asn6 =  $_POST['asn6'];
/*echo $code."\n";
echo $version."\n";
 */
$link = mysql_connect("localhost","root", "") or die('Connection Failure!'); 
$db = mysql_select_db("raspresults");
$sql = "insert into $code"."_address values(null, '$mac','$ipv4', '$asn4', '$ipv6', '$asn6', now())";
echo mysql_query($sql,$link);
?>
