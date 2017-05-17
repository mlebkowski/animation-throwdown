<?php

namespace Nassau\CartoonBattle\Command;

use Nassau\CartoonBattle\Entity\Unit;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncHeroesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:sync-heroes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $game = $this->getContainer()->get('cartoon_battle.game.default');
        $cardRepo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('CartoonBattleBundle:Unit');


        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $connection->connect();
        $query = $connection->prepare('
            INSERT INTO game_hero (id, card_id, name, token_id)
            VALUES (:id, :card_id, :name, :token_id) 
            ON DUPLICATE KEY UPDATE card_id = :card_id, name = :name, token_id = :token_id
        ');

        $data = $game->init() + ['hero_store' => []];

        foreach ($data['hero_store'] as $hero) {
            /** @var Unit $card */
            $card = $cardRepo->find($hero['card_id']);

            $output->writeln(sprintf('Updating <comment>%s</comment>', $card->getName()));

            $query->execute([
                'id' => $hero['id'],
                'card_id' => $card->getId(),
                'name' => $card->getName(),
                'token_id' => $hero['token_id'],
            ]);
        }

    }

}
