<?php

namespace Nassau\CartoonBattle\Services\Farming;

use GuzzleHttp\Exception\TransferException;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarmingLog;
use Nassau\CartoonBattle\Services\Farming\Chores\InitChore;
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
     * @var InitChore
     */
    private $initChore;

    public function __construct(\ArrayObject $chores, GameFactory $factory, InitChore $initChore)
    {
        $this->chores = array_reverse($chores->getArrayCopy());
        $this->factory = $factory;
        $this->initChore = $initChore;
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

        try {
            // you always need to init the game before anything:
            $this->initChore->make($game, $configuration, $logWriter);

            if ($game->getArenaLevel() < $configuration->getMinLevel()) {
                $logWriter(sprintf(
                    'You need to be at least %d arena level to use this tool.',
                    $configuration->getMinLevel()
                ));

                $configuration->setEnabled(false);

                return;
            }

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
