<?php defined('WTHR') OR die('No direct script access.');

return array(
	'caching'		 => TRUE,
	'cache_dir'		 => ROOTDIR.'cache',
	'latitude'		 => 0,
	'cache_lifetime' => 3600,

	'windrose' => array(
		array('0', array('Северо-', 'Северный')),
		array('90', 'Восточный'),
		array('180',   array('Юго-', 'Южный')),
		array('270', 'Западный'),
		array('360', array('Северо-', 'Северный')),
	),
);
