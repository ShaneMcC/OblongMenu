<?php

require_once(dirname(__FILE__) . '/phpquery/phpQuery/phpQuery.php');

class MenuParser {
	private $url = '';
	private $parsed = array();

	public function __construct($url) {
		$this->url = $url;
		$this->parse();
	}

	public function get() {
		return $this->parsed;
	}

	private function parse() {
		$page = file_get_contents($this->url);
		preg_match_all('#<!-- JSON Menu  <script type="text/javascript">  var menu = (.*);  </script>  -->#Ums', $page, $m);

		$parsed = array();

		for ($i = 0; $i < count($m[0]); $i++) {
			$menu = trim($m[1][$i]);
			$menu = str_replace("'", '"', $menu);
			$menu = json_decode($menu, true);

			$thisMenu = array();
			foreach (array('date', 'meal', 'cafe', 'url') as $bit) {
				$thisMenu[$bit] = $menu[$bit];
			}
			$thisMenu['stations'] = array();

			foreach ($menu['stations'] as $station) {
				$thisMenu['stations'][$station['name']] = $station;
			}

			$parsed[$thisMenu['meal']] = $thisMenu;
		}

		if (preg_match_all('#">(Lunch|Breakfast|Dinner)</span></span></b><span[ ]?><span style="font-size: small; ">: ([0-9:]+(?:am|pm)) - ([0-9:]+(?:am|pm))</span#', $page, $m)) {
			for ($i = 0; $i < count($m[0]); $i++) {
				$type = strtolower($m[1][$i]);
				if (isset($parsed[$type])) {
					$parsed[$type]['opening'] = $m[2][$i];
					$parsed[$type]['closing'] = $m[3][$i];
				}
			}
		}

		$this->parsed = $parsed;
	}

	private function getPage($url) {
		return $this->getDocument(file_get_contents($this->url));
	}

	/**
	 * Clean up an element.
	 *
	 * @return a clean element as a string.
	 */
	private function cleanElement($element, $decode = false) {
		// Fail.
		$result = trim(preg_replace('#[^\s\w\d-._/\\\'*()<>{}\[\]@&;!"%^]#i', '', $element->html()));

		if ($decode) {
			$result = preg_replace_callback("/&#?([0-9]+);/", function($m) { return mb_convert_encoding('&#' . $m[1] . ';', "UTF-8", "HTML-ENTITIES"); }, $result);
			$result = html_entity_decode($result);
		}

		return $result;
	}

	/**
	 * Get the requested page, logging in if needed.
	 *
	 * @param $url URL of page to get.
	 * @param $justGet (Default: false) Just get the page, don't try to auth.
	 */
	private function getDocument($html) {
		$config = array('indent' => TRUE,
		                'wrap' => 0,
		                'output-xhtml' => true,
		                'clean' => true,
		                'numeric-entities' => true,
		                'char-encoding' => 'ascii',
		                'input-encoding' => 'ascii',
		);
		$tidy = tidy_parse_string($html, $config);
		$tidy->cleanRepair();
		$html = $tidy->value;
		return phpQuery::newDocument($html);
	}



}

?>
