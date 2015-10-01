<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

/**
 * Cache handling should be there
 */
class ConfigurationProvider
{
    /**
     * @var ConfigurationCreator
     */
    private $configurationCreator;

    /**
     * @param ConfigurationCreator $configurationCreator
     */
    public function __construct(ConfigurationCreator $configurationCreator)
    {
        $this->configurationCreator = $configurationCreator;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configurationCreator->create();
    }
}
