<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 10:57
 */

// If you want overwrite some constants you can do it in config.php
if (is_file('../ConstConfig.php') && is_readable('../ConstConfig.php')) {
    include('../ConstConfig.php');
}

require_once( '../autoload.php' );

/**
 * Let's get the config
 *
 * @var $config \T3fx\Config
 */
$config = \T3fx\Config::getInstance();

/**
 * In the configuration should be a part where the default controller and
 * action is defined. We try now to find and initialize the default controller.
 * The default controller should make all further routing actions.
 */
$bootstrapConfig = $config->getApplication();
if(!isset( $bootstrapConfig["controller"] )) {
	die( 'No default controller found.' );
}

/**
 * We found the default controller. Now we initialize it and search for the default action
 */
$bootstrap = new $bootstrapConfig["controller"]();
if(!method_exists($bootstrap, $bootstrapConfig["action"])) {
	die( 'No default action found.' );
}

/**
 * Do we have params which we have to give the default action? If yes we use call_user_func_array
 */
$defaultAction = $bootstrapConfig["action"];
if(
	isset( $bootstrapConfig["params"] ) &&
	is_array($bootstrapConfig["params"]) &&
	!empty( $bootstrapConfig["params"] )
) {
	call_user_func_array(array($bootstrap, $defaultAction), $bootstrapConfig["params"]);
}
else {
	$bootstrap->$defaultAction();
}
