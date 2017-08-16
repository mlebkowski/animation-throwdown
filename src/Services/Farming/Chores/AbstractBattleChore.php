<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
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

            $configuration->addResult($nextTarget, $winner, $this->stripResult($result));

            $loot = $winner ? $this->lootExtractor->extractLoot($this->normalizeRewards($result)) : [];

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

    private function stripResult(array $result)
    {
        return array_diff_key($result, [
            'active_events' => false,
            'item_data' => false,
            'current_missions' => false,
            'mission_completions' => false,
            'pvp_ranks' => false,
            'store_items' => false,
            'user_units' => false,
        ]);
    }

    private function normalizeRewards(array $result)
    {
        return array_replace([
            'items' => isset($result['new_items']) ? $result['new_items'] : [],
        ], isset($result['battle_data']['rewards'][0]) ? $result['battle_data']['rewards'][0] : []);
    }

}
