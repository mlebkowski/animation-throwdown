<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170819130153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming CHANGE settings settings LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', CHANGE adventure_missions adventure_missions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', CHANGE arena_heroes arena_heroes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming CHANGE settings settings LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\', CHANGE adventure_missions adventure_missions LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\', CHANGE arena_heroes arena_heroes LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\'');
    }
}
