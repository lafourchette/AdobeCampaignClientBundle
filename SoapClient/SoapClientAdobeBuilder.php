<?php

namespace LaFourchette\AdobeCampaignClientBundle\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\SoapClient\Client as SoapClient;

class SoapClientAdobeBuilder
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param SoapClient $soapClientSession
     * @param SoapClient $soapClientRecipient
     *
     * @return SoapClient
     */
    public function build()
    {
        $soapClient = new SoapClient(null, array(
            'location' => $this->config['base_uri'].'/nl/jsp/soaprouter.jsp';
        ));

        $response = $soapClient->logon(array(
            'sessiontoken' => '',
            'strLogin' => $config['login'],
            'strPassword' => $config['password'],
            'elemParameters' => '',
        ));

        var_dump($response);die;

        $soapClient->setHttpHeaders(array(
            'X-Security-Token' => $response->pstrSecurityToken,
            'cookie' => '__sessiontoken=' . $response->pstrSessionToken,
        ));

        return $soapClient;
    }
}
