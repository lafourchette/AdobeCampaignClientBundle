<?php

namespace LaFourchette\AdobeCampaignClientBundle\Behat\Context\Initializer;

use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;
use Behat\LaFourchette\Helper\ResourcesHelper;

use LaFourchette\AdobeCampaignClientBundle\Behat\Context\SoapClientAwareInterface;
use LaFourchette\AdobeCampaignClientBundle\SoapClient\Client as SoapClient;

class SoapClientAwareInitializer implements InitializerInterface
{
    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * @var ResourcesHelper
     */
    private $resourcesHelper;

    /**
     * @param SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient, ResourcesHelper $resourcesHelper)
    {
        $this->soapClient = $soapClient;
        $this->resourcesHelper = $resourcesHelper;
    }

    /**
     * @param ContextInterface $context
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof SoapClientAwareInterface;
    }

    /**
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        $context->setSoapClient($this->soapClient);
        $context->setResourcesHelper($this->resourcesHelper);
    }
}
