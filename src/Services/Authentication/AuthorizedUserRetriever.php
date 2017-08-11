<?php


namespace Nassau\CartoonBattle\Services\Authentication;


use Nassau\CartoonBattle\Entity\Game\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthorizedUserRetriever
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User
     */
    public function getAuthorizedUser()
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if (false === $user instanceof User) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }

}