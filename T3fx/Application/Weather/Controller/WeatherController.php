<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 09/07/16
 * Time: 09:54
 */

namespace T3fx\Application\Weather\Controller;

class WeatherController {

	/**
	 * @var \T3fx\Application\Weather\Domain\Repository\WeatherRepository
	 * @inject
	 */
	protected $WeatherRepository;

	/**
	 * WeatherController constructor.
	 */
	public function __construct () {
		// Till we have in auto inject
		$this->WeatherRepository = new \T3fx\Application\Weather\Domain\Repository\WeatherRepository();
	}

	/**
	 * Index Action
	 */
	public function indexAction () {
	}

	/**
	 * Get action returns the weather information of the given time as JSON
	 */
	public function getAction() {

		// TODO: GET and POST parameters over processor method for secure request parameter handling
		$time = $_GET["time"];
		if(empty($time)) {
			$time = time();
		}
		$weather = $this->WeatherRepository->getWeatherForTime($time);

		// TODO: check, compile and return the result
		echo $weather["json"];
	}


}