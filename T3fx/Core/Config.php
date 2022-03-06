<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 10:29
 */

namespace T3fx;

class Config extends \T3fx\Library\Pattern\Singleton
{

    /**
     * Loaded configuration is stored here
     *
     * @var array $configuration
     */
    protected $configuration = [];

    /**
     * Class is called by getInstance and loads the configuration for the located domain.
     *
     * TODO: Make application configurable and merge with default config
     */
    protected function init()
    {
        $configuration       = $this->getLocalConfiguration();
        $domain              = \T3fx\Library\Connector\Http\Info::getInstance()->getHeaderValue('host');
        $this->configuration = $configuration["default"];

        if (isset($configuration[$domain]) && !empty($configuration[$domain])) {
            $this->configuration = array_replace_recursive($this->configuration, $configuration[$domain]);
        }
    }

    /**
     * Loads the configuration file and returns its content
     *
     * @return mixed
     */
    private function getLocalConfiguration()
    {
        return require DOCUMENT_ROOT . 'LocalConfig.php';
    }

    /**
     * Returns the configuration values of database
     */
    public function getDatabaseConfig()
    {
        return $this->configuration["database"];
    }

    /**
     * Returns the configured application with controller, default action and parameters for the default action
     */
    public function getApplication()
    {
        return $this->configuration["application"];
    }


    public function getApplicationConfig()
    {
        $params = func_get_args();
        $config = $this->configuration["applications"];

        foreach ($params as $param) {
            if (!isset($config[$param])) {
                return;
            }
            $config = $config[$param];
        }

        return $config;
    }

}