<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170525122353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE guild_moderators (guild_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D08F497C5F2131EF (guild_id), INDEX IDX_D08F497CA76ED395 (user_id), PRIMARY KEY(guild_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guild_moderators ADD CONSTRAINT FK_D08F497C5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_moderators ADD CONSTRAINT FK_D08F497CA76ED395 FOREIGN KEY (user_id) REFERENCES kuma_users (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE guild_moderators');
    }
}
