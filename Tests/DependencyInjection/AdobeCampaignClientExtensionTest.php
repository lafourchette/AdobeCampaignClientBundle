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
            'base_uri' => 'http://foo.com',
            'login' => 'webservice',
            'password' => 'password',
            'schemas' => array(
                'query_def' => array(
                    'name' => 'query_def',
                    'schema' => 'xtk:queryDef'
                ),
            )
        ));

        $this->assertContainerBuilderHasService('adobe_campaign_client.client.query_def');
        $this->assertContainerBuilderHasService('adobe_campaign_client.creator.client');
    }
}
