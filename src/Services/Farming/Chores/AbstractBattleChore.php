<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\DTO\Battle;
use Nassau\CartoonBattle\Services\Farming\DTO\BattleTarget;
use Nassau\CartoonBattle\Services\Farming\DTO\FailedToStartBattle;
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
        $targetGenerator = $this->shouldDoBattle($game, $configuration, $logWriter);

        /** @var $nextTarget BattleTarget */
        while (null !== ($nextTarget = $targetGenerator->current())) {
            $logWriter(sprintf(
                'Playing %s battle: <comment>%s</comment>… ',
                $nextTarget->getType(),
                $nextTarget->getLabel()
            ), false);

            $battle = $this->startBattle($nextTarget, $game);

            sleep(1);

            if (false === $battle->isSuccess()) {
                $logWriter(sprintf('<error>Unable to start battle</error> %s', $battle->getMessage()));
                $targetGenerator->send(new FailedToStartBattle($battle->getMessage()));

                continue;
            }

            $result = $game->skipBattle($battle->getId());

            $winner = (bool)$result['battle_data']['winner'];

            $loot = $winner ? $this->lootExtractor->extractLoot($this->normalizeRewards($result)) : [];

            $this->reportBattleResult($winner, $loot, $logWriter);

            $targetGenerator->send(null);
        }
    }

    /**
     * @param Game        $game
     * @param UserFarming $configuration
     * @param \Closure    $logWriter
     *
     * @return \Generator|BattleTarget[]
     */
    abstract protected function shouldDoBattle(Game $game, UserFarming $configuration, \Closure $logWriter);

    /**
     * @param BattleTarget $target
     * @param Game $game
     *
     * @return Battle
     */
    abstract protected function startBattle(BattleTarget $target, Game $game);

    /**
     * @param bool $winner
     * @param array $loot
     * @param \Closure $logWriter
     * @return void
     */
    private function reportBattleResult($winner, array $loot, \Closure $logWriter)
    {
        if (false === $winner) {
            $logWriter('<error>Defeat</error>');

            return ;
        }

        $logWriter('<info>Victory</info>', false);

        if (sizeof($loot)) {
            $logWriter(sprintf('. Loot: %s', implode(', ', $loot)), false);
        }

        $logWriter("");
    }

    private function normalizeRewards(array $result)
    {
        return array_replace([
            'items' => isset($result['new_items']) ? $result['new_items'] : [],
        ], isset($result['battle_data']['rewards'][0]) ? $result['battle_data']['rewards'][0] : []);
    }

}
