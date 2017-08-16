<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class ItemsExtractor implements LootHandler
{
    private $items = [
        "1002" => "energy refill (+5)",
        "1003" => "energy refill (+10)",
        "1023" => "arena refill (+5)",
        "1024" => "arena refill (+10)",
        "1031" => "research speedup (+1h)",
        "1032" => "research speedup (+8h)",
        "1033" => "research speedup (+24h)",
        "200001" => "mythic stones",
        "200002" => "epic stones",
        "200003" => "legendary stones",
        "21000" => "wonder wharf coins",
        "200005" => "golden turds",
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
