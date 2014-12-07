<?php
		echo $time = hexdec(substr(md5('b827ebb0dec6'),25))%24;
		echo $hour = intval(date('H'));
?>
