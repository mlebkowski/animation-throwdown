<?php

namespace Nassau\CartoonBattle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Services\Kongregate\GetGameCredentials;
use Nassau\CartoonBattle\Services\Request\CorsResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class KongregateAuthorizationController
{
    /**
     * @var GetGameCredentials
     */
    private $auth;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(GetGameCredentials $auth, EntityManagerInterface $em)
    {
        $this->auth = $auth;
        $this->em = $em;
    }


    public function getUser(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->auth->getUser($username, $password);

        if (null === $user) {
            return new CorsResponse(['error' => true], $request, JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->em->persist(User::fromUser($user));

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            // oh well
        }

        return new CorsResponse([
            'user_id' => $user->getUserId(),
            'password' => $user->getPassword(),
            'name' => $user->getName(),
        ], $request);

    }
}
