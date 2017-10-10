<?php

namespace Nassau\CartoonBattle\Services\Farming;

use Nassau\CartoonBattle\Services\Game\DTO\Item;
use Nassau\CartoonBattle\Services\Game\Game;

class Refill
{
    const MODE_ADVENTURE = 'adventure';
    const MODE_ARENA = 'arena';

    private $items;

    public function __construct($mode)
    {
        $this->items = [
            self::MODE_ADVENTURE => [Item::ENERGY_REFILL_5 => 5, Item::ENERGY_REFILL_10 => 10],
            self::MODE_ARENA => [Item::ARENA_REFILL_5 => 5, Item::ARENA_REFILL_10 => 10],
        ][$mode];
    }

    public function refillEnergy($remainingEnergy, Game $game, \Closure $logWriter)
    {
        foreach ($this->items as $item => $energy) {
            while ($remainingEnergy < 0 && $game->getItemCount($item)) {
                $logWriter(sprintf('Refilling adventure energy: <comment>%d</comment>', $energy));
                $game('useItem', ['item_id' => $item, 'number' => 1]);

                $remainingEnergy += $energy;
            }
        }

        return $remainingEnergy;

    }
}