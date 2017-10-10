<?php


namespace Nassau\CartoonBattle\Services\Farming\Chores;


use Nassau\CartoonBattle\Services\Farming\LootExtractor\LootExtractor;
use Nassau\CartoonBattle\Services\Farming\Refill;
use Nassau\CartoonBattle\Services\Game\Game;

abstract class AbstractRefillableBattleChore extends AbstractBattleChore
{
    private $refill;

    public function __construct(LootExtractor $lootExtractor, Refill $refill)
    {
        parent::__construct($lootExtractor);
        $this->refill = $refill;
    }

    protected function refill($remainingEnergy, Game $game, \Closure $logWriter)
    {
        return $this->refill->refillEnergy($remainingEnergy, $game, $logWriter);
    }


}