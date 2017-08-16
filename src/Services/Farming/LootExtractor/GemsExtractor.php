<?php


namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class GemsExtractor implements LootHandler
{

    public function formatLoot($data)
    {
        yield $data ? sprintf("%d Gems", $data) : null;
    }
}