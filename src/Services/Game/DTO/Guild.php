<?php


namespace Nassau\CartoonBattle\Services\Game\DTO;


class Guild
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['faction_id'];
    }

    public function getName()
    {
        return $this->data['name'];
    }
}