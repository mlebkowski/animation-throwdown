<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

use Nassau\CartoonBattle\Services\Game\DTO\Item;

class ItemsExtractor implements LootHandler
{
    private $items = [
        Item::ENERGY_REFILL_5 => "energy refill (+5)",
        Item::ENERGY_REFILL_10 => "energy refill (+10)",
        Item::ARENA_REFILL_5 => "arena refill (+5)",
        Item::ARENA_REFILL_10 => "arena refill (+10)",
        Item::RESEARCH_SPEEDUP_1 => "research speedup (+1h)",
        Item::RESEARCH_SPEEDUP_8 => "research speedup (+8h)",
        Item::RESEARCH_SPEEDUP_24 => "research speedup (+24h)",
        Item::MYTHIC_STONE => "mythic stones",
        Item::EPIC_STONE => "epic stones",
        Item::LEGENDARY_STONE => "legendary stones",
        Item::WONDER_WHARF_COINS => "wonder wharf coins",
        Item::GOLDEN_TURDS => "golden turds",
    ];

    /**
     * @param mixed $data
     * @return \Generator|string[]
     */
    public function formatLoot($data)
    {
        foreach ((array)$data as $item) {
            $item = array_replace(['id' => null, 'number' => 0], (array)$item);

            if ($item['number'] && isset($this->items[$item['id']])) {
                yield sprintf("%d %s", $item['number'], $this->items[$item['id']]);
            }
        }
    }
}
