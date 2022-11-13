<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 09/07/16
 * Time: 09:54
 */

namespace T3fx\Application\Weather\Controller;

use T3fx\Application\Weather\Domain\Repository\IndoorWeatherIpcorrectionRepository;
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
     * @var IndoorWeatherIpcorrectionRepository
     */
    protected $indoorWeatherIpcorrectionRepository;

    /**
     * WeatherController constructor.
     */
    public function __construct()
    {
        defined('TABLE_PREFIX') or define('TABLE_PREFIX', 'tx_weather_domain_model_');

        $this->WeatherRepository                   = new WeatherRepository();
        $this->indoorWeatherIpsRepository          = new IndoorWeatherIpsRepository();
        $this->indoorWeatherRepository             = new IndoorWeatherRepository();
        $this->indoorWeatherIpcorrectionRepository = new IndoorWeatherIpcorrectionRepository();
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
                $url = 'http://' . $ip["ip"] . '/json';
                try {
                    $indoorWeather = json_decode((string)\T3fx\Library\Connector\Http\Curl::Get($url), true);
                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                    continue;
                }

                if (is_array($indoorWeather)) {
                    $indoorWeather           = array_change_key_case($indoorWeather, CASE_LOWER);
                    $indoorWeather["ip"]     = $ip["uid"];
                    $indoorWeather["crdate"] = time();
                    $indoorWeather["tstamp"] = $indoorWeather["crdate"];
                    $this->indoorWeatherRepository->insert($indoorWeather);
                } else {
                    echo 'No indoor weather?' . PHP_EOL;
                }
            }
        }
    }

    public function showIndoorTemperatureAction()
    {
        $this->initView('Weather');

        // At first create a mapping for the IPs and labels
        $labels    = [0 => 'Date'];
        $ipMapping = [];
        $ips       = $this->indoorWeatherIpsRepository->findAll();
        foreach ($ips as $ipRecord) {
            $labels[$ipRecord["ip"]]     = $ipRecord["name"];
            $ipMapping[$ipRecord["uid"]] = $ipRecord["ip"];
        }

        // Start the temperature and humidity records with labels as first row
        $temperatureData = [$labels];
        $humidityData    = [$labels];

        // Create a mapping array between uid and IP for easy mapping
        $idIpMapping = [];
        foreach ($ips as $ip) {
            $idIpMapping[$ip["uid"]] = $ip["ip"];
        }

        // Get all records from the DB
        $weaterRecords      = $this->indoorWeatherRepository->findAll();
        $weatherCorrections = $this->indoorWeatherIpcorrectionRepository->findAll();

        // Replace int key with ip
        foreach ($weatherCorrections as $key => $correction) {
            $weatherCorrections[$ipMapping[$correction["ip"]]] = $correction;
            unset($weatherCorrections[$key]);
        }

        // Now create all the weather records
        foreach ($weaterRecords as $weaterRecord) {
            $correction = $weatherCorrections[$ipMapping[$weaterRecord["ip"]]];
            $crdata     = date('Y-m-d H:i', $weaterRecord["crdate"]);

            // Create temperature records
            $temperatureData[$crdata]                                    = $temperatureData[$crdata] ?? [
                date(
                    'd.m. H:i',
                    $weaterRecord["crdate"]
                )
            ];
            $temperatureData[$crdata][$idIpMapping[$weaterRecord["ip"]]] = (int)$weaterRecord["temperature"] + (int)$correction["temperature"];

            // Create humidity records
            $humidityData[$crdata]                                    = $humidityData[$crdata] ?? [
                date(
                    'd.m. H:i',
                    $weaterRecord["crdate"]
                )
            ];
            $humidityData[$crdata][$idIpMapping[$weaterRecord["ip"]]] = (int)$weaterRecord["humidity"] + (int)$correction["humidity"];
        }

        $this->fillUpArrayHoles($humidityData);
        $this->fillUpArrayHoles($temperatureData);

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

    /**
     * Fill the holes in the array. Whatever that means
     *
     * @param $array
     *
     * @return void
     */
    protected function fillUpArrayHoles(&$array)
    {
        $keys       = array_keys(reset($array));
        $lastValues = [];
        foreach ($keys as $key) {
            $lastValues[$key] = (int)$array[1][$key];
        }

        foreach ($array as $key => $value) {
            foreach ($keys as $kKey) {
                if (!isset($value[$kKey])) {
                    $array[$key][$kKey] = $lastValues[$kKey];
                }
                $lastValues[$kKey] = $array[$key][$kKey];
            }
        }
    }
}
