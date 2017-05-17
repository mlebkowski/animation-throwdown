<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Nassau\CartoonBattle\Entity\Unit;

class InventoryCard
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Enemy
     */
    private $enemy;

    /**
     * @var Unit
     */
    private $unit;

    /**
     * @var integer
     */
    private $level = 1;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Enemy
     */
    public function getEnemy()
    {
        return $this->enemy;
    }

    /**
     * @param Enemy $enemy
     *
     * @return $this
     */
    public function setEnemy(Enemy $enemy)
    {
        $this->enemy = $enemy;

        return $this;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     *
     * @return $this
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = (int)$level;

        return $this;
    }

}
