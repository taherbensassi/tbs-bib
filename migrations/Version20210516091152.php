<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210516091152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_module_revision (id INT AUTO_INCREMENT NOT NULL, revision_by_id INT NOT NULL, tbs_module_id INT NOT NULL, note VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, status INT NOT NULL, INDEX IDX_65C391487690A3B (revision_by_id), INDEX IDX_65C3914D744EA0F (tbs_module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbs_module_revision ADD CONSTRAINT FK_65C391487690A3B FOREIGN KEY (revision_by_id) REFERENCES tbs_user (id)');
        $this->addSql('ALTER TABLE tbs_module_revision ADD CONSTRAINT FK_65C3914D744EA0F FOREIGN KEY (tbs_module_id) REFERENCES tbs_module (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbs_module_revision');
    }
}
