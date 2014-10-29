<?php
	function dolog($msg){
		$timezone = date_default_timezone_get();
		$time = date('Y-m-d H:i:s');
		$handle = fopen('./getpic.log', 'ab');
		//fwrite($handle, $msg);
		$msg = '['.$timezone.']['.$time.'] '.$msg."\r\n";
		if (flock($handle, LOCK_EX)) {
			fwrite($handle, "$msg");
			flock($handle, LOCK_UN);
		} else {
			return false;
			//echo "Couldn't lock the file !";
		}
		fclose($handle);
	}
