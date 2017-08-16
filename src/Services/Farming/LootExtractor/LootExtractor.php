<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class LootExtractor
{
    /**
     * @var LootHandler[]
     */
    private $handlers;

    /**
     * @param LootHandler[] $handlers
     */
    public function __construct($handlers)
    {
        $this->handlers = $handlers;
    }

    public function extractLoot(array $result, $normalized = false)
    {
        $rewards = $normalized ? $result : $this->normalizeRewards($result);

        foreach ($rewards as $type => $value) {
            if (false === isset($this->handlers[$type])) {
                continue ;
            }

            foreach ($this->handlers[$type]->formatLoot($value) as $formattedItem) {
                yield $formattedItem;
            };
        }
    }

    /**
     * @param array $result
     * @return array
     */
    private function normalizeRewards(array $result)
    {
        return array_replace([
            'items' => isset($result['new_items']) ? $result['new_items'] : [],
        ], isset($result['battle_data']['rewards'][0]) ? $result['battle_data']['rewards'][0] : []);
    }
}
