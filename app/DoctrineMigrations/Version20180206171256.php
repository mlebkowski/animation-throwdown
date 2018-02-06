<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180206171256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming ADD hero_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_user_farming ADD CONSTRAINT FK_258F8E3645B0BCD FOREIGN KEY (hero_id) REFERENCES game_hero (id)');
        $this->addSql('CREATE INDEX IDX_258F8E3645B0BCD ON game_user_farming (hero_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming DROP FOREIGN KEY FK_258F8E3645B0BCD');
        $this->addSql('DROP INDEX IDX_258F8E3645B0BCD ON game_user_farming');
        $this->addSql('ALTER TABLE game_user_farming DROP hero_id');
    }
}
