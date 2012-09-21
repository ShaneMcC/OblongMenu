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
		preg_match('#<!-- JSON Menu  <script type="text/javascript">  var menu = (.*);  </script>  -->#Ums', $page, $m);

		$menu = trim($m[1]);
		$menu = str_replace("'", '"', $menu);
		$menu = json_decode($menu, true);

		$parsed = array();
		foreach (array('date', 'meal', 'cafe', 'url') as $i) {
			$parsed[$i] = $menu[$i];
		}
		$parsed['stations'] = array();

		foreach ($menu['stations'] as $station) {
			$parsed['stations'][$station['name']] = $station;
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