<?php

namespace LaFourchette\AdobeCampaignClientBundle\SoapClient;

use BeSimple\SoapClient\SoapClient;
use BeSimple\SoapClient\SoapRequest;

use LaFourchette\AdobeCampaignClientBundle\SoapClient\Token;

class Client extends SoapClient
{
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
