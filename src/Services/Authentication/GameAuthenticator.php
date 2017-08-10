<?php

namespace Nassau\CartoonBattle\Services\Authentication;

use Nassau\CartoonBattle\Services\Game\DTO\User;
use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class GameAuthenticator extends AbstractGuardAuthenticator
{

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Credentials required', Response::HTTP_UNAUTHORIZED);
    }

    public function getCredentials(Request $request)
    {
        $userId = $request->get('user_id');
        $password = $request->get('password');

        if (!$userId || !$password) {
            return null;
        }

        return new User($userId, $password);
    }

    /**
     * @param SynapseUserInterface $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials->getUserId());

        return $user;

    }

    /**
     * @param SynapseUserInterface $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $user->getPassword() === $credentials->getPassword();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response('Invalid credentials', Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
