<?php

namespace Nassau\CartoonBattle\Controller\Paypal;

use Doctrine\ORM\EntityManagerInterface;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
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
        $subscription = (string)$request->request->get('subscr_id');

        /** @var UserFarming[] $results */
        $results = $this->em->createQueryBuilder()
            ->select('farming')
            ->from('CartoonBattleBundle:Game\Farming\UserFarming', 'farming')
            ->join('farming.user', 'user')
            ->where('user.userId = :userId')
            ->setParameter('userId', $request->request->get('custom'))
            ->orWhere('farming.subscription = :subscription')
            ->setParameter('subscription', $subscription)
            ->getQuery()
            ->getResult();

        $date = $request->request->get('payment_date');

        $errors = array_filter([
            'subscr_payment' !== $request->request->get('txn_type'),
            'Completed' !== $request->request->get('payment_status'),
            "" === $subscription,
            0 === sizeof($results),
        ]);

        if (0 === sizeof($errors)) {
            foreach ($results as $farming) {
                $days = $farming->getReferralCode() ? $farming->getReferralCode()->getDays() : 45;

                $this->em->persist($farming
                    ->setSubscription($subscription)
                    ->setExpiresAt((new \DateTime($date))->modify("+$days days"))
                );
            }
        }

        $this->em->persist(new InstantNotification($request->getContent()));
        $this->em->flush();

        return new Response();
    }


}