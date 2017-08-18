<?php

namespace Nassau\CartoonBattle\Controller\Paypal;

use Doctrine\ORM\EntityManagerInterface;
use Nassau\CartoonBattle\Entity\Paypal\InstantNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstantPaymentNotificationController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listener(Request $request)
    {
        $this->em->persist(new InstantNotification($request->getContent()));
        $this->em->flush();

        return new Response();
    }


}