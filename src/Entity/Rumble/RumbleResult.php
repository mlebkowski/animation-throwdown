<?php

namespace Nassau\CartoonBattle\Entity\Rumble;

class RumbleResult
{
    private $id;

    private $rumble;

    private $userId;

    private $matchNumber;

    private $points;

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


}