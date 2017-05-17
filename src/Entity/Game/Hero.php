<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Nassau\CartoonBattle\Entity\Unit;

class Hero
{
    private $id;

    private $card;

    private $name;

    private $tokenId;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Unit
     */
    public function getCard()
    {
        return $this->card;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }


}
