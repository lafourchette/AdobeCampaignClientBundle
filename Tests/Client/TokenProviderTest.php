<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\Client;

use LaFourchette\AdobeCampaignClientBundle\Client\TokenProvider;

class TokenProviderTest extends \PHPUnit_Framework_TestCase
{
    private $tokenCreator;

    private $provider;

    public function setUp()
    {
        $this->tokenCreator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\TokenCreator');

        $this->provider = new TokenProvider(
            $this->tokenCreator->reveal()
        );
    }

    public function testGetToken()
    {
        $token = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Tests\Client\Token');

        $this->tokenCreator->create()
            ->willReturn($token->reveal());

        $this->assertEquals($token->reveal(), $this->provider->getToken());
    }
}
