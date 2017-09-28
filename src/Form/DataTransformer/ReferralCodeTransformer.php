<?php

namespace Nassau\CartoonBattle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectRepository;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarmingReferralCode;
use Symfony\Component\Form\DataTransformerInterface;

class ReferralCodeTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function transform($value)
    {
        if ($value instanceof UserFarmingReferralCode) {
            return $value->getName();
        }

        return "";
    }

    public function reverseTransform($value)
    {
        return $this->repository->findOneBy(['name' => $value, 'enabled' => 1]);
    }
}