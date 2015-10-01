<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

use LaFourchette\AdobeCampaignClientBundle\Client\Client;

/**
 * Create a Client for a given schema
 */
class ClientCreator
{
    const WSDL_PATH = '/nl/jsp/schemawsdl.jsp?schema=%s&__sessiontoken=%s';

    /**
     * @var ConfigurationProvider
     */
    private $configurationProvider;

    /**
     * @var ClientInstantiator
     */
    private $clientInstantiator;

    /**
     * @param ConfigurationProvider $configurationProvider
     * @param ClientInstantiator $clientInstantiator
     */
    public function __construct(ConfigurationProvider $configurationProvider, ClientInstantiator $clientInstantiator)
    {
        $this->configurationProvider = $configurationProvider;
        $this->clientInstantiator = $clientInstantiator;
    }

    /**
     * Build a Client based on a schema name
     *
     * @param string $schema
     *
     * @return Client
     */
    public function create($schema)
    {
        $configuration = $this->configurationProvider->getConfiguration();

        $client = $this->clientInstantiator->instantiateClient(
            $configuration->getBaseUri().sprintf(self::WSDL_PATH, $schema, $configuration->getSession())
        );

        $client->setSchema($schema);
        $client->setConfiguration($configuration);
        $client->setHttpHeaders(array(
            'X-Security-Token' => $configuration->getSecurity(),
            'cookie' => '__sessiontoken=' . $configuration->getSession(),
        ));

        return $client;
    }
}
