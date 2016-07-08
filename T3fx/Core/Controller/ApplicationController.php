<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 14:40
 */

namespace T3fx\Controller;

class ApplicationController {

	public function StandardAction () {

		if(!$pathInfo = \T3fx\Library\Connector\Http\Info::getInstance()->getPathInfo()) {
			return $this->indexAction();
		}

		$appName = $pathInfo[1];
		$path = 'Application/' . $appName . '/';
		if(!is_dir(DOCUMENT_ROOT . $path)) {
			return $this->indexAction();
		}

		$namespace = '\\T3fx\\Application\\' . $appName . '\\Controller\\';
		$controller = ucfirst(strtolower($appName)) . 'Controller';
		switch(count($pathInfo)) {
			case 2:
				$action = $pathInfo[2] . 'Action';
				$controller = $namespace . $controller;
				break;
			case 3:
				$action = $pathInfo[3] . 'Action';
				$controller = $namespace . ucfirst(strtolower($pathInfo[2])) . 'Controller';;
				break;
			default:
				$action = 'indexAction';
				$controller = $namespace . $controller;
				break;
		}

		$bootstrap = new $controller();
		if(method_exists($bootstrap, $action)) {
			return $bootstrap->$action();
		}

		return $this->indexAction();
	}

	public function indexAction () {
		echo 'Standard-indexAction';
	}
}