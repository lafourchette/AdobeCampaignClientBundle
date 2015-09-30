<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\Client\ClientCreator;
use LaFourchette\AdobeCampaignClientBundle\Client\Client;

class ClientCreatorTest extends \PHPUnit_Framework_TestCase
{
    private $tokenProvider;
    private $clientInstantiator;
    private $creator;

    public function setUp()
    {
        $this->tokenProvider = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\TokenProvider');
        $this->clientInstantiator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ClientInstantiator');

        $this->creator = new ClientCreator(
            $this->tokenProvider->reveal(),
            $this->clientInstantiator->reveal()
        );
    }

    public function testCreateClient()
    {
        $token = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Token');

        $this->tokenProvider->getToken()
            ->willReturn($token->reveal());

        $token->getBaseUri()
            ->willReturn('http://foo.com')->shouldBeCalled();

        $token->getSession()
            ->willReturn('sessionToken')->shouldBeCalled();

        $token->getSecurity()
            ->willReturn('securityToken')->shouldBeCalled();


        $client = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\Client');
        $this->clientInstantiator->instantiateClient('http://foo.com/nl/jsp/schemawsdl.jsp?schema=foo&__sessiontoken=sessionToken')
            ->willReturn($client->reveal());

        $client->setSchema('foo')
            ->shouldBeCalled();

        $client->setToken($token)
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
