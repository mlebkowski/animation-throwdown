<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Game\Game;

class WatchAdsChore implements FarmingChore
{

    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        $ads = 0;

        while (false === $this->check($game) && $ads++ < 3) {
            $logWriter('Watching an ad to Boost your chances');
            $game->recordAdBoost();
            sleep(8);
        }
    }

    private function check(Game $game)
    {
        $result = $game('getBoostLevel');
        $userData = isset($result['user_data']) ? $result['user_data'] : null;

        if ((int)$userData['boost_level'] === 3 && $userData['boost_end_time'] > time() + 60 * 60 * 1.5) {
            return true;
        }

        return false;
    }
}
