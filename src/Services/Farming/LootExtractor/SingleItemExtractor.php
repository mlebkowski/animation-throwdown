<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class SingleItemExtractor implements LootHandler
{
    /**
     * @var ItemsExtractor
     */
    private $itemsExtractor;

    public function __construct(ItemsExtractor $itemsExtractor)
    {
        $this->itemsExtractor = $itemsExtractor;
    }


    /**
     * @param mixed $data
     *
     * @return \Generator|string[]
     */
    public function formatLoot($data)
    {
        if (!$data) {
            return;
        }

        foreach ($this->itemsExtractor->formatLoot(is_array(reset($data)) ? $data : [$data]) as $loot) {
            yield $loot;
        }
    }
}