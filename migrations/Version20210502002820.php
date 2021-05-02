<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502002820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_files (id INT AUTO_INCREMENT NOT NULL, tbs_module_image_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, INDEX IDX_39C7E661569A09B5 (tbs_module_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbs_files ADD CONSTRAINT FK_39C7E661569A09B5 FOREIGN KEY (tbs_module_image_id) REFERENCES tbs_module (id)');
        $this->addSql('DROP TABLE tbs_file');
        $this->addSql('ALTER TABLE tbs_module DROP images_file_names');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_file (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, size INT NOT NULL, INDEX IDX_CB3FD3B63DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tbs_file ADD CONSTRAINT FK_CB3FD3B63DA5256D FOREIGN KEY (image_id) REFERENCES tbs_module (id)');
        $this->addSql('DROP TABLE tbs_files');
        $this->addSql('ALTER TABLE tbs_module ADD images_file_names LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
