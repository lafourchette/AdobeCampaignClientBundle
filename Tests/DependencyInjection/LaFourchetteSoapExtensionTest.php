<?php

namespace LaFourchette\AdobeCampaignClientBundle\Tests\Behat\Extension;

use LaFourchette\AdobeCampaignClientBundle\DependencyInjection\AdobeCampaignClientExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class AdobeCampaignClientExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return array(
            new AdobeCampaignClientExtension()
        );
    }

    /**
     * @test
     */
    public function after_loading_the_Service_client_has_been_set()
    {
        $this->load(array(
            'wsdls' => array(
                'session' => array(
                    'path' => 'wsdl.xml',
                    'login' => 'webservice',
                    'password' => 'password',
                ),
                'recipient' => array(
                    'path' => 'wsdl.xml',
                    'login' => 'webservice',
                    'password' => 'password',
                ),
            )
        ));

        $this->assertContainerBuilderHasService('la_fourchette_soap.client.recipient');
        $this->assertContainerBuilderHasService('la_fourchette_soap.client.session');
        $this->assertContainerBuilderHasService('la_fourchette_soap.soap_client.adobe_builder');
        $this->assertContainerBuilderHasService('la_fourchette_soap.client');
    }
}
