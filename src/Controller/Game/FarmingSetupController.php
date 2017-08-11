<?php

namespace Nassau\CartoonBattle\Controller\Game;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Form\FarmingType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FarmingSetupController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory, Serializer $serializer)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->serializer = $serializer;
    }


    public function setup(Request $request, User $user)
    {
        $farming = $user->getFarming() ?: (new UserFarming())->setUser($user);

        $form = $this->formFactory->create(FarmingType::class, $farming, ['csrf_protection' => false]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = 'Successfully saved';
            $this->em->persist($farming);
            $this->em->flush();
        }

        $logs = [];

        if ($farming->getId()) {
            $logs = $this->em->createQueryBuilder()
                ->select('log')
                ->from('CartoonBattleBundle:Game\UserFarmingLog', 'log')
                ->where('log.userFarming = :farming')
                ->orderBy('log.createdAt', 'DESC')
                ->setMaxResults(15)
                ->setParameter('farming', $farming)
                ->getQuery()
                ->getResult();
        }

        return new JsonResponse([
            'message' => isset($message) ? $message : null,
            'form' => $this->normalizeForm($form->createView()),
            'farming' => $this->serializer->toArray($farming, (new SerializationContext)->setGroups(['settings'])),
            'logs' => $this->serializer->toArray($logs)
        ]);
    }

    private function normalizeForm(FormView $form)
    {
        $compound = $form->vars['compound'];
        return array_filter([
            'name' => $compound ? null : $form->vars['full_name'],
            'label' => $form->vars['label'],
            'value' => $compound ? null : $form->vars['value'],
            'choices' => isset($form->vars['choices']) ? $form->vars['choices'] : null,
            'children' => array_map([$this,'normalizeForm'], $form->children),
            'errors' => array_map(function (FormError $e) {
                return $e->getMessage();
            }, iterator_to_array($form->vars['errors'])),
        ]);
    }

}