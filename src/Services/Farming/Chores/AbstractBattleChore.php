<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Farming\LootExtractor\LootExtractor;
use Nassau\CartoonBattle\Services\Game\Game;

abstract class AbstractBattleChore implements FarmingChore
{

    /**
     * @var LootExtractor
     */
    private $lootExtractor;

    /**
     * @param LootExtractor $lootExtractor
     */
    public function __construct(LootExtractor $lootExtractor)
    {
        $this->lootExtractor = $lootExtractor;
    }


    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        foreach ($this->shouldDoBattle($game, $configuration) as $nextTarget) {
            $logWriter(sprintf(
                'Playing %s battle: <comment>%s</comment>… ',
                $nextTarget->getType(),
                $nextTarget->getLabel()
            ), false);

            $battleId = $this->startBattle($nextTarget, $game);

            sleep(1);

            $result = $game->skipBattle($battleId);

            $winner = (bool)$result['battle_data']['winner'];

            $configuration->addResult($nextTarget, $winner, $result);

            $loot = $winner ? $this->lootExtractor->extractLoot($result) : null;

            $this->reportBattleResult($winner, $loot, $logWriter);
        }
    }

    /**
     * @param Game $game
     * @param UserFarming $configuration
     *
     * @return \Generator|BattleTarget[]
     */
    abstract protected function shouldDoBattle(Game $game, UserFarming $configuration);

    /**
     * @param BattleTarget $target
     * @param Game $game
     *
     * @return string
     */
    abstract protected function startBattle(BattleTarget $target, Game $game);

    /**
     * @param bool $winner
     * @param \Iterator $loot
     * @param \Closure $logWriter
     * @return void
     */
    private function reportBattleResult($winner, \Iterator $loot = null, \Closure $logWriter)
    {
        if (false === $winner) {
            $logWriter('<error>Defeat</error>');

            return ;
        }

        $logWriter('<info>Victory</info>', false);

        $loot = array_filter(iterator_to_array($loot));
        if (sizeof($loot)) {
            $logWriter(sprintf('. Loot: <comment>%s</comment>', implode('</comment>, <comment>', $loot)), false);
        }

        $logWriter("");
    }

}
