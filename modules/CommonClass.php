<?php

namespace modules;

use libraries\PDO;
use libraries\Config;

class CommonClass
{

	function __construct()
	{
		PDO::Connection( Config::get("config.databases.default"));
	}

	function __destruct()
	{
		PDO::Close();
	}

}