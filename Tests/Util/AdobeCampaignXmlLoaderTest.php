<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\Util;

use LaFourchette\AdobeCampaignClientBundle\Util\AdobeCampaignXmlLoader;

class AdobeCampaignXmlLoaderTest
{

    private $payload = <<<EOT
<?xml version='1.0'?>
<SOAP-ENV:Envelope xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:ns='urn:xtk:session' xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'>
  <SOAP-ENV:Body>
    <LogonResponse xmlns='urn:xtk:session' SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'>
      <pstrSecurityToken xsi:type='xsd:string'>securityToken</pstrSecurityToken>
    </LogonResponse>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOT;

    public function testLoadXml()
    {
        $this->assertInstanceof('\SimpleXMLElement', AdobeCampaignXmlLoader::loadXml($this->payload));
    }
}
