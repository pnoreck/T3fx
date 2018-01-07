<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 14:40
 */

namespace T3fx\Controller;

class ApplicationController
{


    public function StandardAction()
    {

        /**
         * TODO: This should be the index action of the bootstrap controller. Refactoring required
         */
        if (!$pathInfo = \T3fx\Library\Connector\Http\Info::getInstance()->getPathInfo()) {
            return $this->indexAction();
        }

        // First check failed. We found nothing and return the index action of this controller.
        $appName = $pathInfo[1];
        $path    = 'Application/' . $appName . '/';
        if (!is_dir(DOCUMENT_ROOT . $path)) {
            return $this->indexAction();
        }

        // Application path exist now we build the namespace and controller name
        $namespace  = '\\T3fx\\Application\\' . $appName . '\\Controller\\';
        $controller = $appName . 'Controller';

        switch (count($pathInfo)) {

            // We have a path with 2 segments. First is the controller and second is the action
            case 2:
                $action     = $pathInfo[2] . 'Action';
                $controller = $namespace . $controller;
                break;

            // We have a path with 3 segments. First is the application, second the controller and third the action
            case 3:
                $action     = $pathInfo[3] . 'Action';
                $controller = $namespace . ucfirst(strtolower($pathInfo[2])) . 'Controller';;
                break;

            // We have only one or an undefined count of segments. We call the index action of the application.
            default:
                $action     = 'indexAction';
                $controller = $namespace . $controller;
                break;
        }

        $bootstrap = new $controller();

        /**
         * TODO: Inject repositories, clean GET and POST variables and do some other magic
         *
         * TODO: Don't return the result of the controller. Call the view to render the output and check the
         * result of the controller. If the controller result is an array we will return this as json.
         */
        if (method_exists($bootstrap, $action)) {
            $result = $bootstrap->$action();
            if (is_array($result)) {
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
        }

        return $this->indexAction();
    }

    /**
     * @TODO: Implement some fancy output / an error page
     *
     * @return void
     */
    public function indexAction()
    {
        echo 'Standard-indexAction';
    }
}