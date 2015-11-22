<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

/**
 * Instantiate SOAP client class, untestable so nothing else
 */
class ClientInstantiator
{
    /**
     * Instantiate an AdobeCampaign Client
     *
     * @param $wsdlPath
     *
     * @return Client
     */
    public function instantiateClient($wsdlPath)
    {
        return new Client($wsdlPath);
    }

    /**
     * Instantiate a classic php SOAPClient
     *
     * @param $wsdl
     * @param $options
     *
     * @return \SoapClient
     */
    public function instantiateBasicClient($wsdl, $options)
    {
        return new \SoapClient($wsdl, $options);
    }
}
