<?php


namespace Nassau\CartoonBattle\Tests\Chores;

class Unit extends \Nassau\CartoonBattle\Entity\Unit
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRarity()
    {
        return new Rarity;
    }


}