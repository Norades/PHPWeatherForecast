<?php

define('ROOTDIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('WTHR', true);

require_once(ROOTDIR.'bootstrap'.EXT);

$configs = Core::getConfigs();

//set configuration and getting xml of weather data
Library_Weather::__getInstance()
	->setLatitude($configs['latitude'])
	->setLongtitude($configs['longtitude'])
	->setAltitude($configs['altitude'])
	->setCacheMode($configs['caching'])
	->setCacheDir($configs['cache_dir'])
	->setCacheLifetime($configs['cache_lifetime'])
	->getWeatherData();
