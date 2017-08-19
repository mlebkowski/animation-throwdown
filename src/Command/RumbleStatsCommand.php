<?php

namespace Nassau\CartoonBattle\Command;

use Nassau\CartoonBattle\Entity\Game\UserGatherRumbleStats;
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
        /** @var UserGatherRumbleStats[] $queue */
        $queue = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('CartoonBattleBundle:Game\UserGatherRumbleStats')
            ->findBy(['enabled' => true]);

        $gameFactory = $this->getContainer()->get('cartoon_battle.game.factory');

        foreach ($queue as $request) {
            $user = $request->getUser();
            $output->writeln(sprintf('Fetching stats for <comment>%s</comment>', $user->getName()));
            $this->fetchStats($gameFactory->getGame($user), $request->getId());
        }
    }

    private function fetchStats(Game $game, $requestId)
    {
        $rumble = $game->getRumble();

        if (false === $rumble->isActive()) {
            return;
        }

        $stats = $game->getRumbleStats($rumble);

        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $db->prepare('INSERT IGNORE INTO rumble (`id`, `start`, `end`) VALUES (:id, :start, :end)')->execute([
            'id' => $rumble->getId(),
            'start' => $rumble->getStart()->format('Y-m-d H:i:s'),
            'end' => $rumble->getEnd()->modify('-3 days')->format('Y-m-d H:i:s'),
        ]);

        $query = $db->prepare('
            INSERT INTO rumble_result (rumble_id, name, user_id, match_number, points, request_id)
            VALUES (:rumble_id, :name, :user_id, :match_number, :points, :request_id)
            ON DUPLICATE KEY UPDATE points = :points
        ');

        $matchNumber = $rumble->getHighestStartedMatchNumber() ?: 1;

        foreach ($stats as $user) {
            $query->execute([
                'rumble_id' => $rumble->getId(),
                'name' => $user['name'],
                'user_id' => $user['user_id'],
                'match_number' => $matchNumber,
                'points' => (int)$user['stat'],
                'request_id' => $requestId
            ]);
        }

    }

}
