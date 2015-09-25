<?php

namespace LaFourchette\AdobeCampaignClientBundle\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\SoapClient\Client as SoapClient;

class SoapClientAdobeBuilder
{
    /**
     * @param SoapClient $soapClientSession
     * @param SoapClient $soapClientRecipient
     *
     * @return SoapClient
     */
    public function build(SoapClient $soapClientSession, SoapClient $soapClientRecipient)
    {
        $response = $soapClientSession->logon(array(
            'sessiontoken' => '',
            'strLogin' => $soapClientSession->getToken()->getLogin(),
            'strPassword' => $soapClientSession->getToken()->getPassword(),
            'elemParameters' => '',
        ));

        $soapClientRecipient->setHttpHeaders(array(
            'X-Security-Token' => $response->pstrSecurityToken,
            'cookie' => '__sessiontoken=' . $response->pstrSessionToken,
        ));

        return $soapClientRecipient;
    }
}
