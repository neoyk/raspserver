<?php
$now = time();
echo date('Y-m-d H:i:s',$now).' '.$now.'<br>';
$now = floor(time()/3600)*3600;
echo date('Y-m-d H:i:s',$now).' '.$now.'<br>';
$now = '20141122';
echo $now.' '.strtotime($now);
?>
