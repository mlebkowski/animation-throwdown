<?php

namespace Nassau\CartoonBattle\Entity\Rumble;

use Nassau\CartoonBattle\Entity\Game\UserGatherStats;

class RumbleResult
{
    private $id;

    /**
     * @var Rumble
     */
    private $rumble;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $matchNumber;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var UserGatherStats
     */
    private $request;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Rumble
     */
    public function getRumble()
    {
        return $this->rumble;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return integer
     */
    public function getMatchNumber()
    {
        return $this->matchNumber;
    }

    /**
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return UserGatherStats
     */
    public function getRequest()
    {
        return $this->request;
    }


}
