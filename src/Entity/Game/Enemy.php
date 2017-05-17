<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\Timestampable;

class Enemy
{
    use Timestampable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $pvpRating;

    /**
     * @var string
     */
    private $guildName;

    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var integer
     */
    private $commanderLevel;

    /**
     * @var InventoryCard[]|Collection
     */
    private $cards;

    /**
     * @return int
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
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getPvpRating()
    {
        return $this->pvpRating;
    }

    /**
     * @return string
     */
    public function getGuildName()
    {
        return $this->guildName;
    }

    /**
     * @return Hero
     */
    public function getHero()
    {
        return $this->hero;
    }

    /**
     * @return int
     */
    public function getCommanderLevel()
    {
        return $this->commanderLevel;
    }

    /**
     * @return Collection|InventoryCard[]
     */
    public function getCards()
    {
        return $this->cards;
    }

}
