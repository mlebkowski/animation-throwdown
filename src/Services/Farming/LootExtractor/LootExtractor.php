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

    public function extractLoot(array $rewards)
    {
        $loot = [];

        foreach ($rewards as $type => $value) {
            if (false === isset($this->handlers[$type])) {
                continue ;
            }

            foreach ($this->handlers[$type]->formatLoot($value) as $formattedItem) {
                if ($formattedItem) {
                    $loot[$formattedItem] = true;
                }
            }
        }

        return array_map(function ($loot) {
            return sprintf('<comment>%s</comment>', $loot);
        }, array_keys($loot));
    }

}
