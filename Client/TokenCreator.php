<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

use LaFourchette\AdobeCampaignClientBundle\Exception\TokenCreationException;

/**
 * Create a Adobe Token object with security informations
 */
class TokenCreator
{
    const SOAP_MESSAGE_PAYLOAD = <<<EOT
<x:Envelope xmlns:x="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:xtk:session">
    <x:Header/>
    <x:Body>
        <urn:Logon>
            <urn:sessiontoken/>
            <urn:strLogin>%s</urn:strLogin>
            <urn:strPassword>%s</urn:strPassword>
            <urn:elemParameters/>
        </urn:Logon>
    </x:Body>
</x:Envelope>
EOT;

    /**
     * @var ClientInstantiator
     */
    private $clientInstantiator;

    /**
     * @var array Configuration informations
     */
    private $config;

    /**
     * @param ClientInstantiator $clientInstantiator
     * @param array $config
     */
    public function __construct(ClientInstantiator $clientInstantiator, $config)
    {
        $this->clientInstantiator = $clientInstantiator;
        $this->config = $config;
    }

    /**
     * Create a Token object
     *
     * @return Token
     */
    public function create()
    {
        $soapClient = $this->clientInstantiator->instantiateBasicClient(null, array(
            'location' => $this->config['base_uri'].Client::SOAP_ROUTER_PATH,
            'uri' => $this->config['base_uri'],
            'trace' => 1
        ));

        try {
            $response = $soapClient->__doRequest(
                sprintf(self::SOAP_MESSAGE_PAYLOAD, $this->config['login'], $this->config['password']),
                $this->config['base_uri'].Client::SOAP_ROUTER_PATH,
                'xtk:session#Logon',
                1
            );
        } catch(\Exception $e) {
            throw new TokenCreationException($e->getMessage(), $e->getCode(), $e);
        }

        if (null === $response) {
            throw new TokenCreationException('Empty response received');
        }

        $response = str_ireplace(array('SOAP-ENV:', 'SOAP:'), '', str_replace('xmlns=', 'ns=', $response));

        $xmlResponse = simplexml_load_string($response);

        $xmlSessionToken = $xmlResponse->xpath('/Envelope/Body/LogonResponse/pstrSessionToken');
        $session = array_pop($xmlSessionToken)->__toString();

        $xmlSecurityToken = $xmlResponse->xpath('/Envelope/Body/LogonResponse/pstrSecurityToken');
        $security = array_pop($xmlSecurityToken)->__toString();

        $token = new Token();
        $token->setBaseUri($this->config['base_uri']);
        $token->setSecurity($security);
        $token->setSession($session);

        return $token;
    }
}
