<?php

namespace Nassau\CartoonBattle\Services;

class Assets
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function all()
    {
        return [
            'cards' => $this->getCards(),
            'power_cards' => $this->getPowerCards(),
            'combos' => $this->getCombos(),
            'mythics' => $this->getMythics(),
            'missions' => $this->getMissions(),
            'levels' => $this->getLevels(),
        ];
    }

    public function getCards()
    {
        return $this("cards");
    }

    public function getPowerCards()
    {
        return $this("cards_finalform");
    }

    public function getCombos()
    {
        return $this("combos");
    }

    public function getMythics()
    {
        return $this("cards_mythic");
    }

    public function getMissions()
    {
        return $this("missions");
    }

    public function getLevels()
    {
        return $this("levels");
    }

    function __invoke($path)
    {
        return file_get_contents(sprintf("%s/assets/%s.xml", $this->url, $path));
    }


}

