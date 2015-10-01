<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

class Configuration
{
    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var string
     */
    private $session;

    /**
     * @var string
     */
    private $security;

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $security
     */
    public function setSecurity($security)
    {
        $this->security = $security;
    }

    /**
     * @return string
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @param string $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }
}
