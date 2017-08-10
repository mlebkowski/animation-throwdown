<?php

namespace Nassau\CartoonBattle\Controller\Game;

use Nassau\CartoonBattle\Services\Authentication\ApiGameFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EchoController
{
    /**
     * @var ApiGameFactory
     */
    private $factory;

    /**
     * @param ApiGameFactory $factory
     */
    public function __construct(ApiGameFactory $factory)
    {
        $this->factory = $factory;
    }


    public function __invoke(Request $request, $message)
    {
        $game = $this->factory->getAuthorizedGame();

        if (null === $game) {
            throw new AccessDeniedHttpException();
        }

        return new JsonResponse($game($message, $request->request->all()));
    }
}
