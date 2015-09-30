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
     * @var string
     */
    private $schema;

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
     * @param string $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
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

    /**
     * Send a custom soap message
     *
     * @param $envelope
     *
     * @return \SimpleXMLElement
     */
    public function doCustomSoapRequest($envelope, $action)
    {
        $response = $this->__doRequest(
            $envelope,
            $this->getToken()->getBaseUri().Client::SOAP_ROUTER_PATH,
            sprintf('%s#%s', $this->getSchema(), $action),
            1
        );

        $response = str_ireplace(array('SOAP-ENV:', 'SOAP:'), '', str_replace('xmlns=', 'ns=', $response));

        return simplexml_load_string($response);
    }
}
