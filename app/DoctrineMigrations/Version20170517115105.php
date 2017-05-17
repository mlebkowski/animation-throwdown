<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170517115105 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_enemy (id INT NOT NULL, hero_id INT NOT NULL, name VARCHAR(255) NOT NULL, guild_name VARCHAR(255) NOT NULL, level INT NOT NULL, pvp_rating INT NOT NULL, commander_level INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C9AC232645B0BCD (hero_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_hero (id INT NOT NULL, card_id INT NOT NULL, name VARCHAR(255) NOT NULL, token_id INT NOT NULL, UNIQUE INDEX UNIQ_BADB02AA4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_enemy_card (id INT AUTO_INCREMENT NOT NULL, unit_id INT NOT NULL, enemy_id INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, user_id INT NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6686BA65A76ED395 (user_id), UNIQUE INDEX UNIQ_6686BA6535C246D5 (password), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_enemy ADD CONSTRAINT FK_C9AC232645B0BCD FOREIGN KEY (hero_id) REFERENCES game_hero (id)');
        $this->addSql('ALTER TABLE game_hero ADD CONSTRAINT FK_BADB02AA4ACC9A20 FOREIGN KEY (card_id) REFERENCES card_unit (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE game_enemy_card ADD CONSTRAINT FK_7D6DFABFF8BD700D FOREIGN KEY (unit_id) REFERENCES card_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_enemy_card ADD CONSTRAINT FK_7D6DFABF900C982F FOREIGN KEY (enemy_id) REFERENCES game_enemy (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7D6DFABFF8BD700D ON game_enemy_card (unit_id)');
        $this->addSql('CREATE INDEX IDX_7D6DFABF900C982F ON game_enemy_card (enemy_id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_enemy_card DROP FOREIGN KEY FK_7D6DFABF900C982F');
        $this->addSql('ALTER TABLE game_enemy DROP FOREIGN KEY FK_C9AC232645B0BCD');
        $this->addSql('DROP TABLE game_enemy');
        $this->addSql('DROP TABLE game_hero');
        $this->addSql('DROP TABLE game_enemy_card');
        $this->addSql('DROP TABLE game_user');
    }
}
