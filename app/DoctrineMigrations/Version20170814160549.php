<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170814160549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_user_farming_referral_code (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, paypal_button VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2717F0C55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_user_farming ADD referral_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_user_farming ADD CONSTRAINT FK_258F8E367EFAA231 FOREIGN KEY (referral_code_id) REFERENCES game_user_farming_referral_code (id)');
        $this->addSql('CREATE INDEX IDX_258F8E367EFAA231 ON game_user_farming (referral_code_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_user_farming DROP FOREIGN KEY FK_258F8E367EFAA231');
        $this->addSql('DROP TABLE game_user_farming_referral_code');
        $this->addSql('DROP INDEX IDX_258F8E367EFAA231 ON game_user_farming');
        $this->addSql('ALTER TABLE game_user_farming DROP referral_code_id');
    }
}
