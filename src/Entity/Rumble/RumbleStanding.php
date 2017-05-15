<?php

namespace Nassau\CartoonBattle\Entity\Rumble;

use Nassau\CartoonBattle\Entity\Guild\Guild;

class RumbleStanding
{
    private $id;

    /**
     * @var Rumble
     */
    private $rumble;

    /**
     * @var integer
     */
    private $place;

    /**
     * @var Guild
     */
    private $guild;

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
     * @param Rumble $rumble
     *
     * @return $this
     */
    public function setRumble(Rumble $rumble)
    {
        $this->rumble = $rumble;

        return $this;
    }

    /**
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param int $place
     *
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Guild
     */
    public function getGuild()
    {
        return $this->guild;
    }

    /**
     * @param Guild $guild
     *
     * @return $this
     */
    public function setGuild(Guild $guild)
    {
        $this->guild = $guild;

        return $this;
    }

}
