<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170525211539 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rumble_result (id INT AUTO_INCREMENT NOT NULL, rumble_id INT NOT NULL, user_id INT NOT NULL, match_number INT NOT NULL, points INT NOT NULL, INDEX IDX_720E535ABA8B6222 (rumble_id), UNIQUE INDEX match_result (rumble_id, user_id, match_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rumble_result ADD CONSTRAINT FK_720E535ABA8B6222 FOREIGN KEY (rumble_id) REFERENCES rumble (id)');

        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('UPDATE rumble SET id = 50007');
        $this->addSql('UPDATE rumble_standing SET rumble_id = 50007');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rumble_result');
    }
}
