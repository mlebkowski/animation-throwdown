<?php

namespace Nassau\CartoonBattle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HarvestDecksCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:harvest-decks')
            ->addArgument('user_id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CartoonBattleBundle:Game\User')
            ->find($input->getArgument('user_id'));

        $db = $this->getContainer()->get('doctrine.dbal.default_connection');

        if (null === $user) {
            throw new \InvalidArgumentException("No such user");
        }

        $game = $this->getContainer()->get('cartoon_battle.game.factory')->getGame($user);

        $enemy = $db->prepare('
            INSERT INTO game_enemy (id, hero_id, name, guild_name, level, pvp_rating, commander_level, created_at, updated_at)
            VALUES (:id, :hero_id, :name, :guild_name, :level, :pvp_rating, :commander_level, NOW(), NOW())
            ON DUPLICATE KEY UPDATE 
              hero_id = :hero_id, name = :name, guild_name = :guild_name, level = :level, pvp_rating = :pvp_rating, commander_level = :commander_level, updated_at = NOW()
        ');

        $truncateDeck = $db->prepare('DELETE FROM game_enemy_card where enemy_id = :enemy_id');
        $insertCard = $db->prepare('INSERT INTO game_enemy_card (unit_id, enemy_id, level) VALUES(:unit_id, :enemy_id, :level)');

        do {
            $target = $game->getRandomHuntingTarget();
            $output->writeln(sprintf('Harvesting <comment>%s</comment> deck...', $target['name']));
            sleep(1);

            $enemyId = $target['user_id'];

            $battleId = $game->startPracticeBattle($enemyId);
            sleep(1);

            $result = $game->skipBattle($battleId)['battle_data'];
            $enemy->execute([
                'id' => $enemyId,
                'hero_id' => $target['hero_xp_id'],
                'name' => $target['name'],
                'guild_name' => (string)$result['enemy_guild_name'],
                'level' => $target['level'],
                'pvp_rating' => $target['pvp_rating'],
                'commander_level' => (int)$target['commander']['level'],
            ]);

            $truncateDeck->execute(['enemy_id' => $enemyId]);

            array_map(function ($unitId, $level) use ($insertCard, $enemyId) {
                $insertCard->execute([
                    'enemy_id' => $enemyId,
                    'unit_id' => $unitId,
                    'level' => $level,
                ]);
            }, explode(',', $result['attacker_deck']), explode(',', $result['attacker_deck_levels']));

            sleep(15);
        } while (true);


    }


}
