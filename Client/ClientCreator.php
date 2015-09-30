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
     * @var TokenProvider
     */
    private $tokenProvider;

    /**
     * @var ClientInstantiator
     */
    private $clientInstantiator;

    /**
     * @param TokenProvider $tokenProvider
     * @param ClientInstantiator $clientInstantiator
     */
    public function __construct(TokenProvider $tokenProvider, ClientInstantiator $clientInstantiator)
    {
        $this->tokenProvider = $tokenProvider;
        $this->clientInstantiator = $clientInstantiator;
    }

    /**
     * Build a Client based on a schema name
     *
     * @param string $schema
     *
     * @return SoapClient
     */
    public function create($schema)
    {
        $token = $this->tokenProvider->getToken();

        $client = $this->clientInstantiator->instantiateClient(
            $token->getBaseUri().sprintf(self::WSDL_PATH, $schema, $token->getSession())
        );

        $client->setToken($token);
        $client->setHttpHeaders(array(
            'X-Security-Token' => $token->getSecurity(),
            'cookie' => '__sessiontoken=' . $token->getSession(),
        ));

        return $client;
    }
}
