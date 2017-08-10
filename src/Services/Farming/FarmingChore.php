<?php

namespace Nassau\CartoonBattle\Services\Farming;

use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

interface FarmingChore
{
    public function make(Game $game, UserFarming $configuration, \Closure $logWriter);
}
