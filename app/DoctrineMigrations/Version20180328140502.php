<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328140502 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rumble_guild_match (id INT AUTO_INCREMENT NOT NULL, request_id VARCHAR(255) NOT NULL, rumble_id INT NOT NULL, match_number INT NOT NULL, name VARCHAR(255) NOT NULL, us_points INT NOT NULL, them_points INT NOT NULL, INDEX IDX_3A463441427EB8A5 (request_id), INDEX IDX_3A463441BA8B6222 (rumble_id), UNIQUE INDEX guild_match (rumble_id, request_id, match_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rumble_guild_match ADD CONSTRAINT FK_3A463441427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_rumble_stats (id)');
        $this->addSql('ALTER TABLE rumble_guild_match ADD CONSTRAINT FK_3A463441BA8B6222 FOREIGN KEY (rumble_id) REFERENCES rumble (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rumble_guild_match');
    }
}
