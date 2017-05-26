<?php

namespace Nassau\CartoonBattle\Command;

use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Services\Game\Game;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RumbleStatsCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('animation-throwdown:rumble-stats')->setAliases(['at:rumble-stats']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User[] $users */
        $users = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('CartoonBattleBundle:Game\User')
            ->findAll();

        $gameFactory = $this->getContainer()->get('cartoon_battle.game.factory');

        foreach ($users as $user) {
            $output->writeln(sprintf('Fetching stats for <comment>%s</comment>', $user->getName()));
            $this->fetchStats($gameFactory->getGame($user));
        }
    }

    private function fetchStats(Game $game)
    {
        $rumble = $game->getRumble();
        $stats = $game->getRumbleStats($rumble);

        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $db->prepare('INSERT IGNORE INTO rumble (`id`, `start`, `end`) VALUES (:id, :start, :end)')->execute([
            'id' => $rumble->getId(),
            'start' => $rumble->getStart()->format('Y-m-d H:i:s'),
            'end' => $rumble->getEnd()->format('Y-m-d H:i:s'),
        ]);

        $query = $db->prepare('
            INSERT INTO rumble_result (rumble_id, user_id, match_number, points)
            VALUES (:rumble_id, :user_id, :match_number, :points)
            ON DUPLICATE KEY UPDATE points = :points
        ');

        $matchNumber = $rumble->getHighestStartedMatchNumber() ?: 1;

        foreach ($stats as $user) {
            $query->execute([
                'rumble_id' => $rumble->getId(),
                'user_id' => $user['user_id'],
                'match_number' => $matchNumber,
                'points' => (int)$user['stat'],
            ]);
        }

    }

}
