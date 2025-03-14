<?php

/*
* Analyses a request, created the right Controller passing it the request
*/

class Dispatcher {

	public static function dispatch($request) {
		return static::dispatchToController($request->getControllerName(),$request);
	}

	public static function dispatchToController($controllerName, $request) {
		$controllerClass = $controllerName . "Controller";
    	$result = new $controllerClass($controllerName, $request);
    	return $result;
		// une requête GET /users doit retourner new UsersController($controllerName, $request)
		// une requête GET /user/1 doit retourner new UserController($controllerName, $request)
	}
}