<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170512142925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card_set (id INT NOT NULL, name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_rarity (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_unit (id INT NOT NULL, image_id BIGINT DEFAULT NULL, rarity_id INT NOT NULL, card_set_id INT NOT NULL, card_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, commander TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_30880EF13DA5256D (image_id), INDEX IDX_30880EF1F3747573 (rarity_id), INDEX IDX_30880EF162C45E6C (card_set_id), INDEX IDX_30880EF1925606E5 (card_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_type (id INT NOT NULL, logo_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_60ED558BF98F144A (logo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_unit ADD CONSTRAINT FK_30880EF13DA5256D FOREIGN KEY (image_id) REFERENCES kuma_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_unit ADD CONSTRAINT FK_30880EF1F3747573 FOREIGN KEY (rarity_id) REFERENCES card_rarity (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE card_unit ADD CONSTRAINT FK_30880EF162C45E6C FOREIGN KEY (card_set_id) REFERENCES card_set (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE card_unit ADD CONSTRAINT FK_30880EF1925606E5 FOREIGN KEY (card_type_id) REFERENCES card_type (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE card_type ADD CONSTRAINT FK_60ED558BF98F144A FOREIGN KEY (logo_id) REFERENCES kuma_media (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO card_rarity VALUES(1, "Common", "common"), (2, "Rare", "rare"), (3, "Epic", "epic"), (4, "Legendary", "legendary"), (5, "Mythic", "mythic")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_unit DROP FOREIGN KEY FK_30880EF162C45E6C');
        $this->addSql('ALTER TABLE card_unit DROP FOREIGN KEY FK_30880EF1F3747573');
        $this->addSql('ALTER TABLE card_unit DROP FOREIGN KEY FK_30880EF1925606E5');
        $this->addSql('DROP TABLE card_set');
        $this->addSql('DROP TABLE card_rarity');
        $this->addSql('DROP TABLE card_unit');
        $this->addSql('DROP TABLE card_type');
    }
}
