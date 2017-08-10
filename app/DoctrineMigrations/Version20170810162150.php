<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170810162150 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_user_farming (id VARCHAR(255) NOT NULL, user_id INT NOT NULL, enabled TINYINT(1) NOT NULL, settings LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', adventure_missions LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_258F8E36A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_user_farming_log (id INT AUTO_INCREMENT NOT NULL, user_farming_id VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BA0B00BE110B0A4 (user_farming_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_user_farming_result (id INT AUTO_INCREMENT NOT NULL, user_farming_id VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, target VARCHAR(255) NOT NULL, winner TINYINT(1) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, INDEX IDX_A10ABB1FE110B0A4 (user_farming_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_user_farming ADD CONSTRAINT FK_258F8E36A76ED395 FOREIGN KEY (user_id) REFERENCES game_user (id)');
        $this->addSql('ALTER TABLE game_user_farming_log ADD CONSTRAINT FK_BA0B00BE110B0A4 FOREIGN KEY (user_farming_id) REFERENCES game_user_farming (id)');
        $this->addSql('ALTER TABLE game_user_farming_result ADD CONSTRAINT FK_A10ABB1FE110B0A4 FOREIGN KEY (user_farming_id) REFERENCES game_user_farming (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming_log DROP FOREIGN KEY FK_BA0B00BE110B0A4');
        $this->addSql('ALTER TABLE game_user_farming_result DROP FOREIGN KEY FK_A10ABB1FE110B0A4');
        $this->addSql('DROP TABLE game_user_farming');
        $this->addSql('DROP TABLE game_user_farming_log');
        $this->addSql('DROP TABLE game_user_farming_result');
    }
}
