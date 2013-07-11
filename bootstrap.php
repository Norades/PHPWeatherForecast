<?php defined('WTHR') OR die('No direct script access.');

define('CLDIR', ROOTDIR.'classes'.DIRECTORY_SEPARATOR);
define('CACHEDIR', ROOTDIR.'cache'.DIRECTORY_SEPARATOR);
define('PLDIR', ROOTDIR.'places'.DIRECTORY_SEPARATOR);

if (!is_dir(CLDIR)) die('Classes dir not found!');
if (!is_dir(CACHEDIR)) die('Cache dir not found!');

require_once(CLDIR.'Core'.EXT);

if (file_exists(ROOTDIR.'config.inc'))
	if (!Core::loadConfig(ROOTDIR, 'config.inc')) die('Config is empty!');
	else {}
else die('Config file not found!');

if (isset($_GET['__place'])) Core::loadConfig(PLDIR, preg_replace('#([\./\\\])*#', '', strtolower((string)$_GET['__place'])));
else die('Place not defined.');

spl_autoload_register(array('Core', 'autoLoader'));
