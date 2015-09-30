<?php

namespace LaFourchette\AdobeCampaignClientBundle\Util;

class AdobeCampaignXmlLoader
{
    /**
     * Create a \SimpleXMLElement from a AdobeCampaign response
     *
     * @param string $xml
     *
     * @return \SimpleXMLElement
     */
    public static function loadXml($xml)
    {
        //Clean xml
        $xml = str_ireplace(array('SOAP-ENV:', 'SOAP:'), '', str_replace('xmlns=', 'ns=', $xml));

        return simplexml_load_string($xml);
    }
}
