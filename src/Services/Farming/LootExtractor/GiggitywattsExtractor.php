<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class GiggitywattsExtractor implements LootHandler
{

    /**
     * @param mixed $data
     * @return \Generator|string[]
     */
    public function formatLoot($data)
    {
        yield $data ? sprintf("%d GiggityWatts", $data) : null;
    }
}
