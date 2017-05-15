<?php

namespace Nassau\CartoonBattle\Entity\Rumble;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Rumble
{
    private $id;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var RumbleStanding[]|Collection
     */
    private $standings;

    public function __construct()
    {
        $this->standings = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     *
     * @return $this
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return $this
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return Collection|RumbleStanding[]
     */
    public function getStandings()
    {
        return $this->standings;
    }

    public function addStanding(RumbleStanding $standing)
    {
        $this->standings->add($standing->setRumble($this));

        return $this;
    }

    public function removeStanding(RumbleStanding $standing)
    {
        $this->standings->removeElement($standing);

        return $this;
    }

    public function __toString()
    {
        return sprintf("%s — %s", $this->start->format('Y-m-d'), $this->end->format('Y-m-d'));
    }


}
