<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


/**
 * Router factory.
 */
class RouterFactory
{


	function __construct()
	{

	}


	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');

		return $router;
	}

}
