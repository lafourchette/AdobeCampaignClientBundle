<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\SoapClient\SoapClientAdobeBuilder;
use LaFourchette\AdobeCampaignClientBundle\SoapClient\Token;

class SoapClientBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testSoapClientAdobeBuildMethod()
    {
        $responseSession = new \stdClass();
        $responseSession->pstrSecurityToken = 'login';
        $responseSession->pstrSessionToken = 'password';

        $token = new Token('login', 'password');

        $soapClientSessionMock = $this
            ->getMockBuilder('LaFourchette\AdobeCampaignClientBundle\SoapClient\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('getToken', 'logon'))
            ->getMock()
            ;
        $soapClientSessionMock
            ->expects($this->any())->method('getToken')->will($this->returnValue($token))
            ;
        $soapClientSessionMock
            ->expects($this->any())->method('logon')->will($this->returnValue($responseSession))
            ;

        $soapClientRecipientMock = $this
            ->getMockBuilder('LaFourchette\AdobeCampaignClientBundle\SoapClient\Client')
            ->disableOriginalConstructor()
            ->getMock()
            ;

        $soapClientRecipientMock
            ->expects($this->any())->method('getToken')->will($this->returnValue($token))
            ;


        $builder = new SoapClientAdobeBuilder();

        $soapClient = $builder->build(
            $soapClientSessionMock,
            $soapClientRecipientMock
        );

        $this->assertInstanceOf('LaFourchette\AdobeCampaignClientBundle\SoapClient\Client', $soapClient);
    }
}
