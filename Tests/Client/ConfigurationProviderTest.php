<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\Client;

use LaFourchette\AdobeCampaignClientBundle\Client\ConfigurationProvider;

class ConfigurationProviderTest extends \PHPUnit_Framework_TestCase
{
    private $configurationCreator;

    private $provider;

    public function setUp()
    {
        $this->configurationCreator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ConfigurationCreator');

        $this->provider = new ConfigurationProvider(
            $this->configurationCreator->reveal()
        );
    }

    public function testGetConfiguration()
    {
        $configuration = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Tests\Client\Configuration');

        $this->configurationCreator->create()
            ->willReturn($configuration->reveal());

        $this->assertEquals($configuration->reveal(), $this->provider->getConfiguration());
    }
}
