<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170515151225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, faction_id INT DEFAULT NULL, recruiting TINYINT(1) NOT NULL, message LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_75407DAB989D9B62 (slug), UNIQUE INDEX UNIQ_75407DAB4448F8DA (faction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rumble (id INT AUTO_INCREMENT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rumble_standing (id INT AUTO_INCREMENT NOT NULL, rumble_id INT NOT NULL, guild_id INT NOT NULL, place INT NOT NULL, INDEX IDX_5E20ED98BA8B6222 (rumble_id), INDEX IDX_5E20ED985F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rumble_standing ADD CONSTRAINT FK_5E20ED98BA8B6222 FOREIGN KEY (rumble_id) REFERENCES rumble (id)');
        $this->addSql('ALTER TABLE rumble_standing ADD CONSTRAINT FK_5E20ED985F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rumble_standing DROP FOREIGN KEY FK_5E20ED985F2131EF');
        $this->addSql('ALTER TABLE rumble_standing DROP FOREIGN KEY FK_5E20ED98BA8B6222');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE rumble');
        $this->addSql('DROP TABLE rumble_standing');
    }
}
