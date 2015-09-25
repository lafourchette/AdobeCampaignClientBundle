<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\Behat\Context\Initializer;

use LaFourchette\AdobeCampaignClientBundle\Behat\Context\Initializer\SoapClientAwareInitializer;

class SoapClientAwareInitializerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $soapClientMock = $this
            ->getMockBuilder('LaFourchette\AdobeCampaignClientBundle\SoapClient\Client')
            ->disableOriginalConstructor()
            ->getMock()
            ;

        $resourcesHelperMock = $this
            ->getMockBuilder('Behat\LaFourchette\Helper\ResourcesHelper')
            ->disableOriginalConstructor()
            ->getMock()
            ;

        $contextMock = $this
            ->getMockBuilder('Behat\Behat\Context\ContextInterface')
            ->disableOriginalConstructor()
            ->setMethods(array('setSoapClient', 'setResourcesHelper'))
            ->getMock()
            ;

        $contextMock
            ->expects($this->once())->method('setSoapClient')->with($soapClientMock);
        $contextMock
            ->expects($this->once())->method('setResourcesHelper')->with($resourcesHelperMock);

        $initializer = new SoapClientAwareInitializer($soapClientMock, $resourcesHelperMock);
        $initializer->initialize($contextMock);
    }
}
