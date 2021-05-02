<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501221528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_file (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, INDEX IDX_CB3FD3B63DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbs_file ADD CONSTRAINT FK_CB3FD3B63DA5256D FOREIGN KEY (image_id) REFERENCES tbs_module (id)');
        $this->addSql('ALTER TABLE tbs_module ADD images_file_names LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD typo3_version INT NOT NULL, ADD link VARCHAR(255) NOT NULL, CHANGE image preview_image_file_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbs_file');
        $this->addSql('ALTER TABLE tbs_module ADD image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP preview_image_file_name, DROP images_file_names, DROP typo3_version, DROP link');
    }
}
