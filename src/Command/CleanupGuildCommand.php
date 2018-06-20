<?php

namespace Nassau\CartoonBattle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupGuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:cleanup-guild');
        $this->addArgument('user');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $game = $this->getGame($input->getArgument('user'));

        $members = $game->getGuildMembers();

        if (sizeof($members) < 50) {
            $output->writeln(sprintf('Guild has only <comment>%d</comment> members, not kicking anyone', sizeof($members)));

            return;
        }

        $members = array_filter($members, function ($member) {
            return "0" === $member['member_role'];
        });

        usort($members, function ($alpha, $bravo) {
            return $alpha['last_update_time'] - $bravo['last_update_time'];
        });

        $toKick = array_shift($members);
        if (!$toKick) {
            $output->writeln('Noone but officers left :(');
            return;
        }

        $inactive = \DateTime::createFromFormat('U', $toKick['last_update_time']);
        $days = (new \DateTime)->diff($inactive)->days;

        if ($days < 7) {
            $output->writeln('Everyone has been active within the last week');
            return ;
        }

        $output->writeln(sprintf(
            'Member <comment>%s</comment> inactive since %s (%d days)',
            $toKick['name'],
            $inactive->format('Y-m-d'),
            $days
        ));

        $game('kickGuildMember', ['target_user_id' => $toKick['user_id']]);
    }


    /**
     * @param int $userId
     *
     * @return \Nassau\CartoonBattle\Services\Game\Game
     */
    private function getGame($userId)
    {
        $user = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('CartoonBattleBundle:Game\User')
            ->findOneBy(['userId' => $userId]);

        if (!$user) {
            throw new \InvalidArgumentException('No such user: ' . $userId);
        }

        return $this->getContainer()->get('cartoon_battle.game.factory')->getGame($user);
    }

}