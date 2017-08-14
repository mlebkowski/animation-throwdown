<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Doctrine\Common\Persistence\ObjectRepository;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Entity\Unit;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Game\Game;

class BasicPacksChore implements FarmingChore
{

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }


    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        $hasMoney = function () use ($game) {
            return $game->hasMoney(.20);
        };

        if (false === $configuration->has($configuration::SETTING_GOLD) || false === $hasMoney()) {
            return ;
        }

        $logWriter('Buying basic packs: ', false);
        $uncommon = [];

        while ($hasMoney()) {
            $newItems = $game->buySinglePackItem();

            if (0 === sizeof($newItems)) {
                $logWriter('<error>Failed to buy a basic pack</error>');
                return;
            }

            sleep(1);

            // log items bought:
            $logWriter(str_repeat('+', sizeof($newItems)), false);

            $units = $this->repository->findBy(['id' => array_column($newItems, 'unit_id')]);
            $uncommon = array_merge($uncommon, array_map(function (Unit $unit) {
                return $unit->getName();
            }, array_filter($units, function (Unit $unit) {
                return false === in_array($unit->getRarity()->getSlug(), ['common', 'rare']);
            })));

            $units = $game('salvageUnitList', ['units' => '[]'])['user_units'];
            $recycleList = array_values(array_map(function ($unit) {
                return (int)$unit['unit_index'];
            }, array_filter($units, function ($unit) {
                 return $unit['rarity'] < 3 && null === $unit['deck_id'] && null === $unit['reserved'];
            })));

            $game('salvageUnitList', ['units' => json_encode($recycleList)]);

            // log items sold
            $logWriter(str_repeat('-', sizeof($recycleList)). ' ', false);

            sleep(1);
        }

        $logWriter("");
        if (sizeof($uncommon)) {
            $logWriter(sprintf('Drops: <comment>%s</comment>', implode('</comment>, <comment>', $uncommon)));
        }
    }

}
