<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

use BeSimple\SoapClient\SoapClient;
use BeSimple\SoapClient\SoapRequest;
use LaFourchette\AdobeCampaignClientBundle\Client\Token;

class Client extends SoapClient
{
    const SOAP_ROUTER_PATH = '/nl/jsp/soaprouter.jsp';

    /**
     * @var Token
     */
    private $token;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param Token
     *
     * @return Client
     */
    public function setToken(Token $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param array $headers
     */
    public function setHttpHeaders(array $headers = array())
    {
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterRequestHeaders(SoapRequest $soapRequest, array $headers)
    {
        $moreHeaders = array();

        foreach ($this->headers as $key => $header) {
            $moreHeaders[] = $key . ':' . $header;
        }

        return array_merge($headers, $moreHeaders);
    }
}
