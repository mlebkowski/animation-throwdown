<?php

namespace Nassau\CartoonBattle\Services\Farming;

use GuzzleHttp\Exception\TransferException;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarmingLog;
use Nassau\CartoonBattle\Services\Farming\LootExtractor\LootExtractor;
use Nassau\CartoonBattle\Services\Game\GameFactory;
use Symfony\Component\Console\Output\OutputInterface;

class FarmingHandler
{

    /**
     * @var UserFarmingLog|null
     */
    private $buffer;

    /**
     * @var FarmingChore[]
     */
    private $chores;

    /**
     * @var GameFactory
     */
    private $factory;

    /**
     * @var LootExtractor
     */
    private $lootExtractor;

    public function __construct(\ArrayObject $chores, GameFactory $factory, LootExtractor $lootExtractor)
    {
        $this->chores = array_reverse($chores->getArrayCopy());
        $this->factory = $factory;
        $this->lootExtractor = $lootExtractor;
    }


    public function farm(UserFarming $configuration, OutputInterface $output)
    {
        $logWriter = function ($string, $newline = true) use ($configuration, $output) {
            if (null === $this->buffer) {
                $this->buffer = $configuration->createNewLog();
            }

            $string = ($newline && "\n" !== substr($string, -1)) ?  $string . "\n" :  $string;

            $this->buffer->appendContent($string);
            $output->write($string);
        };

        $game = $this->factory->getGame($configuration->getUser());

        // fetch current game state
        $result = $game->init();

        $configuration->setComment(implode("\n", [
            $game->getPlayerName(),
            $game->getGuildName() ?: "<no guild>",
            $game->getArenaLevel(),
            $game->getRichness(),
        ]));

        $dailyRewards = [
            'monthly_daily_rewards' => 'Monthly activity rewards',
            'weekly_daily_rewards' => 'Weekly activity rewards'
        ];

        foreach ($dailyRewards as $key => $label) {
            if (isset($result[$key])) {
                $loot = $this->lootExtractor->extractLoot($result[$key]);
                if (sizeof($loot)) {
                    $logWriter(sprintf('%s: %s', $label, implode(', ', $loot)));
                }
            }
        }

        if (false === $game->isSpender() && $game->getArenaLevel() >= 10) {
            $configuration->bumpFreeTrial();
        }

        try {
            foreach ($this->chores as $chore) {
                $chore->make($game, $configuration, $logWriter);
            }
        } catch (FarmingException $e) {
            $logWriter(sprintf('<error>Farming error: %s</error>', $e->getMessage()));
            throw $e;
        } catch (TransferException $e) {
            $logWriter('<error>Network error while farming</error>');
            throw new FarmingException('Network error while farming', 0, $e);
        } catch (\Exception $e) {
            $logWriter('<error>Unknown error prevented this session to continue</error>');
            throw new FarmingException('Unknown error prevented this session to continue', 0, $e);
        } finally {
            $this->buffer = null;

            unset($game);
        }
    }

}
