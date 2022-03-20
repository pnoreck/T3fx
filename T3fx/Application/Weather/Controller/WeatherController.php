<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 09/07/16
 * Time: 09:54
 */

namespace T3fx\Application\Weather\Controller;

use T3fx\Application\Weather\Domain\Repository\IndoorWeatherIpsRepository;
use T3fx\Application\Weather\Domain\Repository\IndoorWeatherRepository;
use T3fx\Application\Weather\Domain\Repository\WeatherRepository;
use T3fx\Core\Controller\AbstractActionController;

class WeatherController extends AbstractActionController
{

    /**
     * @var WeatherRepository
     */
    protected $WeatherRepository;

    /**
     * @var IndoorWeatherIpsRepository
     */
    protected $indoorWeatherIpsRepository;

    /**
     * @var IndoorWeatherRepository
     */
    protected $indoorWeatherRepository;

    /**
     * WeatherController constructor.
     */
    public function __construct()
    {
        defined('TABLE_PREFIX') or define('TABLE_PREFIX', 'tx_weather_domain_model_');

        $this->WeatherRepository          = new WeatherRepository();
        $this->indoorWeatherIpsRepository = new IndoorWeatherIpsRepository();
        $this->indoorWeatherRepository    = new IndoorWeatherRepository();
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $weather = new \T3fx\OpenWeatherMap\Connector();
        $weather->getWeatherByCityID('2657970');
    }

    /**
     * Get action returns the weather information of the given time as JSON
     */
    public function getAction()
    {
        // TODO: GET and POST parameters over processor method for secure request parameter handling
        $time = $_GET["time"];
        if (empty($time)) {
            $time = time();
        }
        $weather    = $this->WeatherRepository->getWeatherForTime($time);
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

    public function indoorAction()
    {
        $ips = $this->indoorWeatherIpsRepository->findAll();
        if (is_iterable($ips)) {
            foreach ($ips as $ip) {
                $url           = 'http://' . $ip["ip"] . '/json';
                $indoorWeather = json_decode((string)\T3fx\Library\Connector\Http\Curl::Get($url), true);
                if (is_array($indoorWeather)) {
                    $indoorWeather           = array_change_key_case($indoorWeather, CASE_LOWER);
                    $indoorWeather["ip"]     = $ip["uid"];
                    $indoorWeather["crdate"] = time();
                    $indoorWeather["tstamp"] = $indoorWeather["crdate"];
                    $this->indoorWeatherRepository->insert($indoorWeather);
                }
            }
        }
    }

    public function showIndoorTemperatureAction()
    {
        $this->initView('Weather');
        $weaterRecords     = $this->indoorWeatherRepository->findAll();
        $temperatureData   = [];
        $temperatureData[] = [
            'Date',
            'Intake temp',
            'Room temp'
        ];

        $humidityData   = [];
        $humidityData[] = [
            'Date',
            'Intake humidity',
            'Room humidity'
        ];

        foreach ($weaterRecords as $weaterRecord) {
            $crdata = date('Y-m-d H:i', $weaterRecord["crdate"]);

            // Create temperature records
            $temperatureData[$crdata]                      = $temperatureData[$crdata] ?? [
                    date(
                        'd.m. H:i',
                        $weaterRecord["crdate"]
                    )
                ];
            $temperatureData[$crdata][$weaterRecord["ip"]] = (int)$weaterRecord["temperature"];

            // Create humidity records
            $humidityData[$crdata]                      = $humidityData[$crdata] ?? [
                    date(
                        'd.m. H:i',
                        $weaterRecord["crdate"]
                    )
                ];
            $humidityData[$crdata][$weaterRecord["ip"]] = (int)$weaterRecord["humidity"];
        }

        // Sort them right
        ksort($temperatureData);
        ksort($humidityData);

        // Fix the array keys for Google Charts
        $cleanedTemperatureData = [];
        foreach ($temperatureData as $record) {
            ksort($record);
            $cleanedTemperatureData[] = array_values($record);
        }

        // Fix the array keys for Google Charts
        $cleanedHumidityData = [];
        foreach ($humidityData as $record) {
            ksort($record);
            $cleanedHumidityData[] = array_values($record);
        }

        return $this->view->render(
            'index.twig',
            [
                'temperatureData' => json_encode($cleanedTemperatureData),
                'humidityData'    => json_encode($cleanedHumidityData),
            ]
        );
    }
}
