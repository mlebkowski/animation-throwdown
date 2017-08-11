<?php

namespace Nassau\CartoonBattle\Services\Authentication;

use Nassau\CartoonBattle\Services\Game\GameFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiGameFactory
{
    /**
     * @var GameFactory
     */
    private $factory;

    /**
     * @var AuthorizedUserRetriever
     */
    private $userRetriever;

    public function __construct(GameFactory $factory, AuthorizedUserRetriever $userRetriever)
    {
        $this->factory = $factory;
        $this->userRetriever = $userRetriever;
    }

    /**
     * @return \Nassau\CartoonBattle\Services\Game\Game|null
     */
    public function getAuthorizedGame()
    {
        try {
            $user = $this->userRetriever->getAuthorizedUser();
        } catch (AccessDeniedHttpException $e) {
            return null;
        }

        return $this->factory->getGame($user);
    }
}
