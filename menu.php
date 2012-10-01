<?php

$obchan = isset($_REQUEST['chan']) ? $_REQUEST['chan'] : '';
$obnick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : '';
$obline = isset($_REQUEST['line']) ? $_REQUEST['line'] : '';

require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/menuparser.php');
require_once(dirname(__FILE__) . '/oblong.php');

/* $message = 'Shane is awesome.';
echo '<menu>', "\n";
echo '<message text="' , htmlspecialchars($message) , '" />', "\n";
echo '</menu>', "\n"; */

$nick = strtolower($obnick);

$bits = explode(' ', $obline);
if (count($bits) > 0 && !empty($bits[0])) {
	$nick = strtolower($bits[0]);
}

if (empty($_REQUEST)) {
	$nick = $defaultNick;
}

$messages = array();

if (isset($people[$nick]['menu'])) {
	foreach ($people[$nick]['menu'] as $m => $details) {
		$items = array();
		$allitems = array();

		if ($details['type'] == 'google') {
			$menu = new MenuParser($m);
			$menu = $menu->get();
			$menu = isset($menu['dinner']) ? $menu['dinner'] : (isset($menu['lunch']) ? $menu['lunch'] : array('date' => 'none'));

			$closed = strtotime('today ' . $menu['closing']);
			$open = strtotime('today ' . $menu['opening']);

			if ($menu['date'] != date('Y/m/d', time())) {
				$items[] = 'No menu available for the current day.';
			} else if ($closed < time()) {
				$items[] = 'Closed.';
			} else {
				foreach ($menu['stations'] as $name => $station) {
					foreach ($station['entries'] as $entry) {
						$item = "\003" . $colours[$entry['color']] . $entry['name'] . "\003";

						if (in_array($name, $people[$nick]['stations']) && in_array($entry['color'], $people[$nick]['colours'])) {
							$items[] = $item;
						}
						$allitems[] = $item;
					}
				}
			}

			if (count($items) == 0) {
				$items = $allitems;
			}

			$note = '';
			if ($open > time()) {
				$note = '(Opens at ' . $menu['opening'] . ' till ' . $menu['closing'] . ') ';
			} else if ($open < time()) {
				$note = '(Closes at ' . $menu['closing'] . ') ';
			}

			$messages[] = $menu['cafe'] . ": " . $note . implode($items, ', ');
		} else if ($details['type'] == 'date') {
			if (isset($details['days'])) {
				if (in_array(date('D'), $details['days'])) {
					$messages[] = $m;
				}
			}
		} else if ($details['type'] == 'static') {
			$messages[] = $m;
		}
	}
}

if (count($messages) == 0) {
	$messages[] = 'No menu available for: ' . $nick;
}

foreach ($messages as $message) {
	$message = "\002[Menu]\002 " . $message;
	if (empty($_REQUEST)) {
		echo $message, "\n";
	} else {
		Oblong($oblongKey, $obchan, $message);
	}
}

?>
