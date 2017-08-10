<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

class ItemsExtractor implements LootHandler
{
    private $items = [
        "200002" => "epic stones",
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
