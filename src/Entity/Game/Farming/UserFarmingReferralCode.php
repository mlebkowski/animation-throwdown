<?php

namespace Nassau\CartoonBattle\Entity\Game\Farming;

class UserFarmingReferralCode
{
    private $id;

    private $name;

    private $paypalButton;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPaypalButton()
    {
        return $this->paypalButton;
    }


}