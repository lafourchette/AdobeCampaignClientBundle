<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

use BeSimple\SoapClient\SoapClient;
use BeSimple\SoapClient\SoapRequest;
use LaFourchette\AdobeCampaignClientBundle\Client\Configuration;
use LaFourchette\AdobeCampaignClientBundle\Util\AdobeCampaignXmlLoader;

class Client extends SoapClient
{
    const SOAP_ROUTER_PATH = '/nl/jsp/soaprouter.jsp';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var string
     */
    private $schema;

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param Configuration
     *
     * @return Client
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;

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
            $this->getConfiguration()->getBaseUri().self::SOAP_ROUTER_PATH,
            sprintf('%s#%s', $this->getSchema(), $action),
            1
        );

        return AdobeCampaignXmlLoader::loadXml($response);
    }
}
