<?php
	function Oblong($key, $chan, $message) {
		$fp = @fsockopen("oblong.md87.co.uk", 3302, $errno, $errstr, 30);
		if ($fp) {
			$out = $key.' '.$chan.' '.substr($message, 0, 460)."\n";
			fwrite($fp, $out);
			fclose($fp);
		}
	}
?>