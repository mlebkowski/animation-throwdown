<?php


namespace Nassau\CartoonBattle\Entity\Rumble;


use Nassau\CartoonBattle\Entity\Game\UserGatherRumbleStats;

class RumbleGuildMatch
{
    private $id;

    /**
     * @var Rumble
     */
    private $rumble;

    /**
     * @var UserGatherRumbleStats
     */
    private $request;

    /**
     * @var integer
     */
    private $matchNumber;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $usPoints;

    /**
     * @var integer
     */
    private $themPoints;

    /**
     * @return mixed
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
     * @return UserGatherRumbleStats
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getMatchNumber()
    {
        return $this->matchNumber;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUsPoints()
    {
        return $this->usPoints;
    }

    /**
     * @return int
     */
    public function getThemPoints()
    {
        return $this->themPoints;
    }

}