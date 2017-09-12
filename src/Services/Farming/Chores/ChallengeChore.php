<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

class ChallengeChore extends AbstractBattleChore
{

    /**
     * @param Game $game
     * @param UserFarming $configuration
     *
     * @return \Generator|BattleTarget[]
     */
    protected function shouldDoBattle(Game $game, UserFarming $configuration)
    {
        if (false === $configuration->has($configuration::SETTING_CHALLENGES)) {
            return ;
        }

        foreach ($this->getActiveChallenges($game) as $id => $name) {
            yield new BattleTarget('challenge', $name, $id);
        }
    }

    /**
     * @param BattleTarget $target
     * @param Game $game
     *
     * @return string
     */
    protected function startBattle(BattleTarget $target, Game $game)
    {
        return $game->startChallengeBattle($target->getTarget());
    }

    private function getActiveChallenges(Game $game)
    {
        $count = 0;

        do {

            $events = array_filter($game->getEvents(), function ($data) {
                return isset($data['challenge'])
                    && $data['challenge_data']['energy']['current_value'] > 0
                    && "1025" !== $data['challenge_data']['id'];
            });

            $currentEvent = reset($events);

            if (!$currentEvent) {
                return;
            }

            yield $currentEvent['challenge'] => $currentEvent['challenge_data']['name'];

        } while ($count++ < 20); // seems like a safe value
    }
}
