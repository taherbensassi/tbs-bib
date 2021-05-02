<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430231229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_export_content_element (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, extension_name VARCHAR(255) NOT NULL, vendor_name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, module VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted TINYINT(1) NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_45199EA9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbs_export_content_element ADD CONSTRAINT FK_45199EA9A76ED395 FOREIGN KEY (user_id) REFERENCES tbs_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbs_export_content_element');
    }
}
