<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504002045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_extension ADD typo3_version LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE tbs_typo3_version DROP FOREIGN KEY FK_A2A6CEE2C66727E7');
        $this->addSql('DROP INDEX IDX_A2A6CEE2C66727E7 ON tbs_typo3_version');
        $this->addSql('ALTER TABLE tbs_typo3_version DROP tbs_extension_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_extension DROP typo3_version');
        $this->addSql('ALTER TABLE tbs_typo3_version ADD tbs_extension_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbs_typo3_version ADD CONSTRAINT FK_A2A6CEE2C66727E7 FOREIGN KEY (tbs_extension_id) REFERENCES tbs_extension (id)');
        $this->addSql('CREATE INDEX IDX_A2A6CEE2C66727E7 ON tbs_typo3_version (tbs_extension_id)');
    }
}
