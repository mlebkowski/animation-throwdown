<?php

namespace Nassau\CartoonBattle\Tests\Refill;

use GuzzleHttp\Client;
use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Services\Game\Game;

class GameStub extends Game
{
    public $calls = [];

    public $items;

    public function __construct(array $items = [])
    {
        parent::__construct(new Client, new User(1,2,3));
        $this->items = $items;
    }

    public function getItemCount($itemId)
    {
        return isset($this->items[$itemId]) ? $this->items[$itemId] : 0;
    }

    public function __invoke($message, array $data = [], array $query = [])
    {
        $this->calls[] = $data;

        $itemId = $data['item_id'];
        if (isset($this->items[$itemId])) {
            $this->items[$itemId]--;
        }
    }


}