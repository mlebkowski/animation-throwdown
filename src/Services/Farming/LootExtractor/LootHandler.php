<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

interface LootHandler
{
    /**
     * @param mixed $data
     * @return \Generator|string[]
     */
    public function formatLoot($data);
}
