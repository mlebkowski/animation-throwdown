<?php

namespace Nassau\CartoonBattle\Services\Farming;

use GuzzleHttp\Exception\TransferException;
use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Entity\Game\UserFarmingLog;
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
     * @param FarmingChore[]|\ArrayObject $chores
     * @param GameFactory $factory
     */
    public function __construct(\ArrayObject $chores, GameFactory $factory)
    {
        $this->chores = array_reverse($chores->getArrayCopy());
        $this->factory = $factory;
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
        $game->init();

        if (false === $game->isSpender()) {
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
