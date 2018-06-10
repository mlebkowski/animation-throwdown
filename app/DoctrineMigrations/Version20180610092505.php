<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180610092505 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_user_gather_stats (id VARCHAR(255) NOT NULL, user_id INT NOT NULL, rumble TINYINT(1) NOT NULL, siege TINYINT(1) NOT NULL, INDEX IDX_B5EE788CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_user_gather_stats ADD CONSTRAINT FK_B5EE788CA76ED395 FOREIGN KEY (user_id) REFERENCES game_user (id)');

        $this->addSql('INSERT INTO game_user_gather_stats (id, user_id, rumble) SELECT id, user_id, enabled FROM game_user_gather_rumble_stats');

        $this->addSql('ALTER TABLE rumble_guild_match DROP FOREIGN KEY FK_3A463441427EB8A5');
        $this->addSql('ALTER TABLE rumble_guild_match ADD CONSTRAINT FK_3A463441427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_stats (id)');
        $this->addSql('ALTER TABLE rumble_result DROP FOREIGN KEY FK_720E535A427EB8A5');
        $this->addSql('ALTER TABLE rumble_result ADD CONSTRAINT FK_720E535A427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_stats (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rumble_guild_match DROP FOREIGN KEY FK_3A463441427EB8A5');
        $this->addSql('ALTER TABLE rumble_guild_match ADD CONSTRAINT FK_3A463441427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_rumble_stats (id)');
        $this->addSql('ALTER TABLE rumble_result DROP FOREIGN KEY FK_720E535A427EB8A5');
        $this->addSql('ALTER TABLE rumble_result ADD CONSTRAINT FK_720E535A427EB8A5 FOREIGN KEY (request_id) REFERENCES game_user_gather_rumble_stats (id)');
        $this->addSql('DROP TABLE game_user_gather_stats');
    }
}
