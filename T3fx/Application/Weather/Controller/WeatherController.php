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
	public function getAction () {

		// TODO: GET and POST parameters over processor method for secure request parameter handling
		$time = $_GET["time"];
		if(empty( $time )) {
			$time = time();
		}
		$weather = $this->WeatherRepository->getWeatherForTime($time);
		$weatherObj = json_decode($weather["json"]);

		$tempC = \T3fx\Library\Converter\Temperature::KelvinToCelsius($weatherObj->main->temp);
		$tempF = \T3fx\Library\Converter\Temperature::CelsiusToFahrenheit($tempC);

		$weatherArray = [
			'city'        => $weatherObj->name,
			'country'     => $weatherObj->sys->country,
			'weather'     => get_object_vars(current($weatherObj->weather)),
			'temperature' => [
				'C' => $tempC,
				'F' => $tempF,
			],
			'humidity'    => $weatherObj->main->humidity,
			'wind'        => get_object_vars($weatherObj->wind),
		    'dt'          => date('Y-m-d H:i:s', $weatherObj->dt),
		];

		return $weatherArray;
	}
}