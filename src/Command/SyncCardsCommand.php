<?php

namespace Nassau\CartoonBattle\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCardsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:sync-cards');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $assets = $this->getContainer()->get('nassau.cartoon_battle.services.assets');
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');

        $cards = new \SimpleXMLElement($assets->getCards());

        $this->updateCardSets($output, $db, $cards);
        $this->updateCardTypes($output, $db, $cards);
        $this->updateCards($output, $db, $cards);
        $this->updateCards($output, $db, new \SimpleXMLElement($assets->getMythics()));
        $this->updateCards($output, $db, new \SimpleXMLElement($assets->getPowerCards()));
    }

    private function updateCardSets(OutputInterface $output, Connection $db, \SimpleXMLElement $cards)
    {
        $query = $db->prepare('INSERT INTO card_set (id, name, visible) VALUES (:id, :name, :visible) ON DUPLICATE KEY UPDATE name = :name, visible = :visible');

        foreach ($cards->xpath('//cardSet') as $cardSet) {
            $output->write(sprintf('Syncing <info>%s</info> card set... ', (string)$cardSet->name));

            $query->execute([
                'id' => (int)$cardSet->id,
                'name' => (string)$cardSet->name,
                'visible' => "1" === (string)$cardSet->visible,
            ]);

            $output->writeln('<comment>ok</comment>');
        }
    }

    private function updateCardTypes(OutputInterface $output, Connection $db, \SimpleXMLElement $cards)
    {
        $query = $db->prepare('INSERT INTO card_type (id, name) VALUES (:id, :name) ON DUPLICATE KEY UPDATE name = :name');

        foreach ($cards->xpath('//unit_type') as $cardType) {
            $output->write(sprintf('Syncing <info>%s</info> card type... ', (string)$cardType->name));

            $query->execute([
                'id' => (int)$cardType->id,
                'name' => (string)$cardType->name,
            ]);

            $output->writeln('<comment>ok</comment>');
        }
    }

    private function updateCards(OutputInterface $output, Connection $db, \SimpleXMLElement $cards)
    {
        $query = $db->prepare('
            INSERT INTO card_unit (id, rarity_id, card_set_id, card_type_id, name, slug, picture, commander, created_at, updated_at)
            VALUES (:id, :rarity, :card_set, :card_type, :name, :slug, :picture, :commander, NOW(), NOW())
            ON DUPLICATE KEY UPDATE updated_at = NOW(),            
                rarity_id = :rarity, card_set_id = :card_set, card_type_id = :card_type, name = :name, slug = :slug, picture = :picture, commander = :commander
        ');

        $slugifier = $this->getContainer()->get('kunstmaan_utilities.slugifier');

        foreach ($cards->xpath('//unit') as $unit) {
            $name = (string)$unit->name;

            $output->write(sprintf('Syncing <info>%s</info> card unit... ', $name));

            $slug = $slugifier->slugify($name);

            $query->execute([
                'id' => (int)$unit->id,
                'rarity' => (int)$unit->rarity,
                'card_set' => (int)$unit->set,
                'card_type' => (int)$unit->type,
                'name' => $name,
                'picture' => (string)$unit->picture,
                'commander' => "1" === (string)$unit->commander,
                'slug' => $slug,
            ]);

            $output->writeln('<comment>ok</comment>');
        }
    }


}
