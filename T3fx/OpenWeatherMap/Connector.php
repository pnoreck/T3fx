<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 11:48
 */

namespace T3fx\OpenWeatherMap;

use T3fx\Config;
use T3fx\Library\Database\Doctrine\DBAL;

class Connector
{
    /**
     * TODO: Write documentation
     */

    protected $apiKey = '';
    protected $apiURL = 'api.openweathermap.org/data/2.5/';
    protected $protocol = 'http://';

    /**
     * Connector constructor.
     */
    public function __construct()
    {
        /** @var Config $config */
        $config = Config::getInstance();
        $this->apiKey = $config->getApplicationConfig('OpenWeatherMap', 'ApiKey');
    }

    public function logWeatherJson($cityID, $json)
    {
        $db          = new DBAL();
        $insertArray = [
            'crdate'  => time(),
            'tstamp'  => time(),
            'city_id' => $cityID,
            'json'    => $json,
        ];

        $db->insertArray('t3fx_weather', $insertArray);
    }

    public function getWeatherByCityID($cityID)
    {
        $apiUrl = $this->buildApiUrl('weather?id=' . $cityID);
        $json   = $this->makeApiCall($apiUrl);
        if ($json) {
            $this->logWeatherJson($cityID, $json);
        } else {

        }
    }

    private function buildApiUrl($callPart)
    {
        return $this->protocol . $this->apiURL . $callPart . '&APPID=' . $this->apiKey;
    }

    private function makeApiCall($url)
    {

        // TODO: make a reusable CURL class out of this
        $ch        = curl_init($url);
        $userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        // curl_setopt($ch, CURLOPT_USERPWD, "user:password");
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $raw = curl_exec($ch);
        curl_close($ch);
        // $raw = '{"coord":{"lon":8.75,"lat":47.5},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10d"}],"base":"stations","main":{"temp":287.61,"pressure":1022,"humidity":89,"temp_min":284.26,"temp_max":289.82},"wind":{"speed":1.56,"deg":266.005},"rain":{"1h":1.1},"clouds":{"all":64},"dt":1466333331,"sys":{"type":3,"id":9109,"message":0.047,"country":"CH","sunrise":1466306846,"sunset":1466364341},"id":2657970,"name":"Winterthur","cod":200}';

        if (!empty($raw)) {
            $result = json_decode($raw);
            if ($result->cod != 200) {
                \T3fx\Library\Logging\File::log("Couldn't fetch weather: " . $result->message);
                return false;
            }

            return $raw;
        }

        \T3fx\Library\Logging\File::log("Couldn't fetch weather: No response");
        return false;
    }
}