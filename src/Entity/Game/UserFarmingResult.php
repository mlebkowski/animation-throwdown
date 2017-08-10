<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Gedmo\Timestampable\Traits\Timestampable;

class UserFarmingResult
{
    use Timestampable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var UserFarming
     */
    private $userFarming;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $target;

    /**
     * @var bool
     */
    private $winner;

    /**
     * @var array
     */
    private $data;

    /**
     * @param UserFarming $userFarming
     * @param string $type
     * @param string $target
     * @param bool $winner
     * @param array $data
     */
    public function __construct(UserFarming $userFarming, $type, $target, $winner, array $data)
    {
        $this->userFarming = $userFarming;
        $this->type = $type;
        $this->target = $target;
        $this->winner = $winner;
        $this->data = $data;
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserFarming
     */
    public function getUserFarming()
    {
        return $this->userFarming;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return bool
     */
    public function isWinner()
    {
        return $this->winner;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
