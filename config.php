<?php
	$oblongKey = '';

	$people = array('md87' => array('menu' => array('http://menu-lon-puddinglane.blogspot.co.uk/' => array('type' => 'google'),
	                                                'http://menu-lon-pavilion.blogspot.co.uk/' => array('type' => 'google'),
	                                               ),
	                                'stations' => array('Carvery Station',
	                                                    'Indulgence Station',
	                                                    'Palace Pier',
	                                                    'The Great Kitchen',
	                                                   ),
	                                'colours' => array('red',
	                                                   'yellow'
	                                                   ),
	                                ),
	                'dataforce' => array('menu' => array('KFC' => array('type' => 'date', 'days' => array('Fri')),
	                                                     'Subway' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu')),
	                                                    ),
	                                    ),
	                'laser' => array('menu' => array('Fish & Chips' => array('type' => 'date', 'days' => array('Fri')),
	                                                 'Something & Chips' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu')),
	                                                ),
	                                ),
	                'zipplet' => array('menu' => array('School Canteen' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri')))),
	                'greboid' => array('menu' => array('Cheese Sandwich' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri')))),
	                'rivernile' => array('menu' => array('Probably soup' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri')))),
	                'demented-idiot' => array('menu' => array('Some kind of sandwich' => array('type' => 'date', 'days' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri')))),
	               );

	if (file_exists(dirname(__FILE__) . '/config.local.php')) {
		require_once(dirname(__FILE__) . '/config.local.php');
	}
?>
