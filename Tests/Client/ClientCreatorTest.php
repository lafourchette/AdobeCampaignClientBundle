<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\Client\ClientCreator;
use LaFourchette\AdobeCampaignClientBundle\Client\Client;

class ClientCreatorTest extends \PHPUnit_Framework_TestCase
{
    private $configurationProvider;
    private $clientInstantiator;
    private $creator;

    public function setUp()
    {
        $this->configurationProvider = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ConfigurationProvider');
        $this->clientInstantiator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ClientInstantiator');

        $this->creator = new ClientCreator(
            $this->configurationProvider->reveal(),
            $this->clientInstantiator->reveal()
        );
    }

    public function testCreateClient()
    {
        $configuration = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Configuration');

        $this->configurationProvider->getConfiguration()
            ->willReturn($configuration->reveal());

        $configuration->getBaseUri()
            ->willReturn('http://foo.com')->shouldBeCalled();

        $configuration->getSession()
            ->willReturn('sessionToken')->shouldBeCalled();

        $configuration->getSecurity()
            ->willReturn('securityToken')->shouldBeCalled();


        $client = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Client');
        $this->clientInstantiator->instantiateClient('http://foo.com/nl/jsp/schemawsdl.jsp?schema=foo&__sessiontoken=sessionToken')
            ->willReturn($client->reveal());

        $client->setSchema('foo')
            ->shouldBeCalled();

        $client->setConfiguration($configuration)
            ->shouldBeCalled();

        $client->setHttpHeaders(array(
            'X-Security-Token' => 'securityToken',
            'cookie' => '__sessiontoken=sessionToken'
        ))->shouldBeCalled();

        $this->assertInstanceOf(
            'LaFourchette\AdobeCampaignClientBundle\Client\Client',
            $this->creator->create('foo')
        );
    }
}
