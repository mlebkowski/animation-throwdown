<?php

namespace Nassau\CartoonBattle\Services\Farming\LootExtractor;

use Doctrine\Common\Persistence\ObjectRepository;
use Nassau\CartoonBattle\Entity\Game\Hero;

class HeroTokenFormatter implements LootHandler
{
    /**
     * @var ObjectRepository
     */
    private $heroRepository;

    /**
     * @param ObjectRepository $heroRepository
     */
    public function __construct(ObjectRepository $heroRepository)
    {
        $this->heroRepository = $heroRepository;
    }


    /**
     * @param mixed $data
     * @return \Generator|string[]
     */
    public function formatLoot($data)
    {
        $data = array_replace([
            'xp' => 0,
            'hero_id' => 0,
        ], (array)$data);

        if (!$data['xp'] || !$data['hero_id']) {
            return;
        }

        /** @var Hero $hero */
        $hero = $this->heroRepository->find($data['hero_id']);

        yield sprintf('%d %s tokens', $data['xp'], $hero ? $hero->getName() : 'hero');
    }
}
