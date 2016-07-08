<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 12:18
 */

namespace T3fx\Library\Converter;


class Temperature {

	/**
	 * Converts the temperature from Celsius to Kelvin
	 *
	 * @param float $celsius
	 * @return float
	 */
	public static function CelsiusToKelvin($celsius) {
		return $celsius + 273.15;
	}

	/**
	 * Converts the temperature from Kelvin to Celsius
	 *
	 * @param float $kelvin
	 * @return float
	 */
	public static function KelvinToCelsius($kelvin) {
		return $kelvin - 273.15;
	}

	/**
	 * Converts the temperature from Celsius to Fahrenheit
	 *
	 * @param float $celsius
	 * @return float
	 */
	public static function CelsiusToFahrenheit($celsius) {
		return $celsius * 1.8 + 32;
	}

	/**
	 * Converts the temperature from Fahrenheit to Celsius
	 *
	 * @param float $fahrenheit
	 * @return float
	 */
	public static function FahrenheitToCelsius($fahrenheit) {
		return ($fahrenheit - 32) / 1.8;
	}


}