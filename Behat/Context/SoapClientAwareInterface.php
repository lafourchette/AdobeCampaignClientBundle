<?php

namespace LaFourchette\AdobeCampaignClientBundle\Behat\Context;

use LaFourchette\AdobeCampaignClientBundle\SoapClient\Client as SoapClient;
use Behat\LaFourchette\ContextInterface\ResourcesHelperAwareInterface;

interface SoapClientAwareInterface extends ResourcesHelperAwareInterface
{
    public function setSoapClient(SoapClient $soapClient);
}
