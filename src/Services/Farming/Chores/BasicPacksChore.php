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
     * @var int
     */
    private $sleep;

    public function __construct(ObjectRepository $repository, $sleep = 1)
    {
        $this->repository = $repository;
        $this->sleep = $sleep;
    }


    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        $shouldRun = function () use ($game) {
            //
            // buy packs if the user has either > 400k coins (80%),
            // or if buying max would keep him above 100k (20%)
            //
            return $game->hasMoney(min(.80, .20 + $game->getInventorySpace() * 2 * pow(10, -3)));
        };

        if (false === $configuration->has($configuration::SETTING_GOLD)) {
            return ;
        }

        while ($shouldRun()) {
            $logWriter('Buying basic packs: ', false);

            $newItems = $game->buyMaxBasicPacks();

            if (0 === sizeof($newItems)) {
                $logWriter('<error>Failed to buy a basic pack</error>');
                return;
            }

            sleep($this->sleep);

            $uncommon = $this->filterUncommonUnits($newItems);

            $logWriter(sprintf('<info>%d</info>, drops: %s',
                sizeof($newItems),
                sizeof($uncommon) ? sprintf('<comment>%s</comment>', implode('</comment>, <comment>', $uncommon)) : 'none'
            ));

            $recycleList = $this->getUnitsToRecycle($game);
            if (sizeof($recycleList)) {
                $game->recycle($recycleList);
            }


            sleep($this->sleep);
        }

    }

    private function filterUncommonUnits(array $newItems)
    {
        $units = $this->repository->findBy(['id' => array_column($newItems, 'unit_id')]);

        $uncommon = array_filter($units, function (Unit $unit) {
            return false === in_array($unit->getRarity()->getSlug(), ['common', 'rare']);
        });

        $names = array_map(function (Unit $unit) {
            return $unit->getName();
        }, $uncommon);

        return $names;
    }

    /**
     * @param Game $game
     *
     * @return array
     */
    private function getUnitsToRecycle(Game $game)
    {
        $units = $game->recycle()['user_units'];

        $common = array_filter($units, function ($unit) {
            return $unit['rarity'] < 3 && null === $unit['deck_id'] && null === $unit['reserved'];
        });

        $recycleList = array_values(array_map(function ($unit) {
            return (int)$unit['unit_index'];
        }, $common));

        return $recycleList;
    }

}
