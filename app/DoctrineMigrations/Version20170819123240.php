<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170819123240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rumble_result ADD request_id VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE rumble_result SET request_id = (SELECT id from game_user_gather_rumble_stats LIMIT 1)');
        $this->addSql('ALTER TABLE rumble_result ADD CONSTRAINT FK_720E535A427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_rumble_stats (id)');
        $this->addSql('CREATE INDEX IDX_720E535A427EB8A5 ON rumble_result (request_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rumble_result DROP FOREIGN KEY FK_720E535A427EB8A5');
        $this->addSql('DROP INDEX IDX_720E535A427EB8A5 ON rumble_result');
        $this->addSql('ALTER TABLE rumble_result DROP request_id, DROP name');
    }
}
