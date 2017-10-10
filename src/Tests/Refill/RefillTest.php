<?php


namespace Nassau\CartoonBattle\Tests\Refill;


use Nassau\CartoonBattle\Services\Farming\Refill;
use Nassau\CartoonBattle\Services\Game\DTO\Item;

class RefillTest extends \PHPUnit_Framework_TestCase
{
    public function test sufficient energy does not get refilled()
    {
        $energy = 10;

        $refill = new Refill(Refill::MODE_ADVENTURE);

        $game = new GameStub([
            Item::ARENA_REFILL_5 => 1,
            Item::ARENA_REFILL_10 => 1,
            Item::ENERGY_REFILL_5 => 1,
            Item::ENERGY_REFILL_10 => 1,
        ]);

        $result = $refill->refillEnergy($energy, $game, function () {});

        $this->assertEquals($energy, $result);
        $this->assertEquals(4, array_sum($game->items));
    }

    public function test all 5 refills are used before any 10()
    {
        $energy = -15;

        $refill = new Refill(Refill::MODE_ADVENTURE);

        $game = new GameStub([
            Item::ENERGY_REFILL_5 => 3,
            Item::ENERGY_REFILL_10 => 1,
        ]);

        $result = $refill->refillEnergy($energy, $game, function () {});

        $this->assertEquals(0, $result);
        $this->assertEquals(1, $game->items[Item::ENERGY_REFILL_10]);
        $this->assertEquals(0, $game->items[Item::ENERGY_REFILL_5]);
        $this->assertEquals(3, sizeof($game->calls));
    }

    public function test negative energy is left after using all refills()
    {
        $energy = -36;

        $refill = new Refill(Refill::MODE_ADVENTURE);

        $game = new GameStub([
            Item::ENERGY_REFILL_5 => 3,
            Item::ENERGY_REFILL_10 => 2,
        ]);

        $result = $refill->refillEnergy($energy, $game, function () {});

        $this->assertEquals(-1, $result);
        $this->assertEquals(0, $game->items[Item::ENERGY_REFILL_10]);
        $this->assertEquals(0, $game->items[Item::ENERGY_REFILL_5]);
        $this->assertEquals(5, sizeof($game->calls));

    }
}
