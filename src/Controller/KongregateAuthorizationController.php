<?php

namespace Nassau\CartoonBattle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Services\Kongregate\GetGameCredentials;
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
        $createResponse = function ($data, $code = JsonResponse::HTTP_OK) use ($request) {
            return new JsonResponse($data, $code, [
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
                'Access-Control-Allow-Origin' => $request->headers->get('origin'),
                'Access-Control-Max-Age' => 3600,
                'Vary' => 'Origin',
            ]);
        };

        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->auth->getUser($username, $password);

        if (null === $user) {
            return $createResponse(['error' => true], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->em->persist(User::fromUser($user));

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            // oh well
        }

        return $createResponse([
            'user_id' => $user->getUserId(),
            'password' => $user->getPassword(),
            'name' => $user->getName(),
        ]);

    }
}
