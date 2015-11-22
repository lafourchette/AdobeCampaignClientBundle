<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\Client\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Client(__DIR__.'/../Resources/queryDef.wsdl');
    }

    public function testSetAndGetConfiguration()
    {
        $configuration = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Configuration');

        $this->client->setConfiguration($configuration->reveal());

        $this->assertEquals($configuration->reveal(), $this->client->getConfiguration());
    }

    public function testSetHttpHeaders()
    {
        $this->client->setHttpHeaders(array('header' => 'value'));

        $refObject   = new \ReflectionObject($this->client);
        $refProperty = $refObject->getProperty('headers');
        $refProperty->setAccessible(true);

        $this->assertSame(array('header' => 'value'), $refProperty->getValue($this->client));
    }

    public function testSetAndGetSchema()
    {
        $this->client->setSchema('xtk:queryDef');

        $this->assertEquals('xtk:queryDef', $this->client->getSchema());
    }

    public function testDoCustomSoapRequest()
    {
        $this->client = $this->getMock(
            'LaFourchette\AdobeCampaignClientBundle\Client\Client',
            array('__doRequest'),
            array(__DIR__.'/../Resources/queryDef.wsdl')
        );

        $this->client
            ->expects($this->once())
            ->method('__doRequest')
            ->with(
                $this->equalTo('<envelope></envelope>'),
                $this->equalTo('http://adobe.uri/nl/jsp/soaprouter.jsp'),
                $this->equalTo('xtk:queryDef#query'),
                $this->equalTo(1)
            )
            ->willReturn('<xml></xml>');

        $configuration = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Configuration');

        $configuration->getBaseUri()->willReturn('http://adobe.uri');

        $this->client->setConfiguration($configuration->reveal());
        $this->client->setSchema('xtk:queryDef');

        $this->assertInstanceOf('SimpleXMLElement', $this->client->doCustomSoapRequest('<envelope></envelope>', 'query'));
    }

}
