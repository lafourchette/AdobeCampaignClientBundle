<?php

namespace LaFourchette\AdobeCampaignClientBundle\Client;

/**
 * Cache handling should be there
 */
class TokenProvider
{
    /**
     * @var TokenCreator
     */
    private $tokenCreator;

    /**
     * @param TokenCreator $tokenCreator
     */
    public function __construct(TokenCreator $tokenCreator)
    {
        $this->tokenCreator = $tokenCreator;
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->tokenCreator->create();
    }
}
