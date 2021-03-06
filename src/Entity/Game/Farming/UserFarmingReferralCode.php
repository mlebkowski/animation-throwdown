<?php

namespace Nassau\CartoonBattle\Entity\Game\Farming;

class UserFarmingReferralCode
{
    private $id;

    private $name;

    private $paypalButton;

    private $freeTier;

    private $days;

    private $minLevel;

    private $enabled;

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

    /**
     * @return boolean
     */
    public function hasFreeTier()
    {
        return $this->freeTier;
    }

    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return integer
     */
    public function getMinLevel()
    {
        return $this->minLevel;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
