<?php defined('WTHR') OR die('No direct script access.');

class Library_Weather extends Abstract_Library
{
	private static $configs = array(
		//Coordinates
		'longtitude'     => NULL,
		'latitude'       => NULL,
		'altitude'	     => NULL,
		
		//Caching
		'cacheMode'	     => FALSE,
		'cacheDir'	     => NULL,
		'cacheLifetime'  => 3600
	);

	//Container for XML-data
	private $forecastXML;

	/**
	* Magic method binder to dumbly set the above variables
	* e.g. set_cache_dir is bound to $this->cache_dir = $x
	* @param string $method The method being called
	* @param array $args The arguments being passed to the magic method
	*/
	
	public function __call($method, $args = null)
	{
		if (substr($method, 0, 3) == 'set') //find "set" in $method...
		{
			$var = lcfirst(substr($method, 3));
			self::$configs[$var] = $args[0];
		} else die("Unknown method: {$method}"); //or die, bitch
		
		return $this;
	}

	/**
	* Perform a retrieval for the Wunderground forecast information
	*/
	
	public function getWeatherData($force = FALSE)
	{
		if (!is_array(self::$configs)) die('Configuration is corrupted!');
		
		$configs = self::$configs;
		$req = "http://api.yr.no/weatherapi/locationforecast/1.8/?lat={$configs['latitude']};lon={$configs['longtitude']};msl={$configs['altitude']}";
		
		if ($configs['cacheDir'] && !$force)
		{
			$cfile = "{$configs['cacheDir']}/WU-{$configs['latitude']}-{$configs['longtitude']}-{$configs['altitude']}.xml";
			$expiry = time() + $configs['cacheLifetime'];
			
			foreach (glob("{$configs['cacheDir']}/*.xml") as $cacheFile) if (filectime($cacheFile) > $expiry) unlink($cacheFile);

			if (!file_exists($cfile))
			{
				$blob = file_get_contents($req);
				
				if (!$blob) die("Invalid return from request to {$req}");
				
				$fh = fopen($cfile, 'w');
				fwrite($fh, $blob);
				fclose($fh);
			}
			
			$this->forecastXML = simplexml_load_file($cfile);
		} else $this->forecastXML = simplexml_load_file($req);
		
		return $this;
	}

	/**
	* Get the forecast of a specific date
	* The date will be rounded backwards to the beginning of the given hour
	* So 2011-01-01 15:43 becomes 2011-01-01 15:00
	* @param int $epoc The epoc of the date to retrieve
	*/
	
	public function getForecast($epoc)
	{
		if (!$this->forecastXML) die("Error: called before getWeatherData()");
		
		$from = date('Y-m-d', $epoc) . 'T' . date('H', $epoc) .':00:00Z';
		$info = array('date' => array('epoc' => $epoc, 'iso' => $from));
		
		if (!$casts = $this->forecastXML->xpath("//time[@from='$from']/location")) return FALSE;
		
		foreach ($casts as $forecast)
		{
			if ($forecast->xpath("//temperature"))
			{
				foreach ((array)$forecast as $key => $branch)
				{
					if ($key == '@attributes') continue;
					
					$branch = (array)$branch; 
					$info[$key] = $branch['@attributes'];
				}
			}
		}
		
		return (count($info) === 1) ? FALSE : $info;
	}
}
