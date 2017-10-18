<?php

namespace Nassau\CartoonBattle\Tests\Chores;

use GuzzleHttp\Client;
use Nassau\CartoonBattle\Services\Game\DTO\User;
use Nassau\CartoonBattle\Services\Game\Game;

class GameStub extends Game
{
    /**
     * @var \Nassau\CartoonBattle\Services\Game\SynapseUserInterface
     */
    private $space;
    /**
     * @var Client
     */
    private $money;

    private $number = 0;

    public function __construct($money, $space)
    {
        parent::__construct(new Client, new User(1,2,3));
        $this->space = $space;
        $this->money = $money;
    }

    public function recycle(array $unitIds = [])
    {
        $this->number++;
        return ['user_units' => []];
    }

    public function hasMoney($percent = .25)
    {
        return round($this->money / 500000, 3) >= round($percent, 3);
    }

    public function getInventorySpace()
    {
        return $this->space;
    }


    public function buyMaxBasicPacks()
    {
        $this->money -= $this->space * 1000;

        return array_map(function ($i) {
            return [
                'unit_id' => $i,
            ];
        }, range(1, $this->space));
    }


    public function getNumberOfPulls()
    {
        return $this->number;
    }


}