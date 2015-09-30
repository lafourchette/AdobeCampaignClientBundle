<?php


namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\Client\TokenCreator;
use Prophecy\Argument;

class TokenCreatorTest extends \PHPUnit_Framework_TestCase
{
    private $clientInstantiator;

    private $creator;

    public function setUp()
    {
        $this->clientInstantiator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ClientInstantiator');

        $this->creator = new TokenCreator(
            $this->clientInstantiator->reveal(),
            array(
                'base_uri' => 'http://foo.com'
            )
        );
    }

    public function testTokenCreationFailBecauseOfClientExpc()
    {
        $client = $this->prophesize('\SoapClient');

        $this->clientInstantiator->instantiateBasicClient(null, array(
            'location' => 'http://foo.com/nl/jsp/soaprouter.jsp',
            'uri' => 'http://foo.com',
            'trace' => 1
        ))->willReturn($client->reveal());

        $client->__doRequest(
            Argument::type('string'),
            'http://foo.com/nl/jsp/soaprouter.jsp',
            'xtk:session#Logon',
            1
        );
    }
}
