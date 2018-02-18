<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

class ChallengeChore extends AbstractBattleChore
{

    /**
     * @param Game        $game
     * @param UserFarming $configuration
     * @param \Closure    $logWriter
     *
     * @return \Generator|BattleTarget[]
     */
    protected function shouldDoBattle(Game $game, UserFarming $configuration, \Closure $logWriter)
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
                if (false === isset($data['challenge_data'])) {
                    return false;
                }

                $trackingEndTime = (int)$data['tracking_end_time'];
                $lastRechargeTime = (int)$data['challenge_data']['energy']['last_recharge_time'];
                $rechargeTime = (int)$data['challenge_data']['energy']['recharge_time'];

                $roundEnds = min($trackingEndTime, $lastRechargeTime + $rechargeTime);

                return $data['challenge_data']['energy']['current_value'] > 0
                    && $trackingEndTime > time()
                    && time() > $roundEnds - (60*90)  // last 90 minutes
                    && "1027" !== $data['challenge_data']['id'];
            });

            $currentEvent = reset($events);

            if (!$currentEvent) {
                return;
            }

            yield $currentEvent['challenge'] => $currentEvent['challenge_data']['name'];

        } while ($count++ < 30); // seems like a safe value
    }
}
