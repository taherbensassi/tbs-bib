<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331154413 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_site_package (id INT AUTO_INCREMENT NOT NULL, typo3_version INT NOT NULL, base_package VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, repository_url VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, author_email VARCHAR(255) NOT NULL, author_company VARCHAR(255) NOT NULL, author_home_page VARCHAR(255) NOT NULL, user VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbs_site_package');
    }
}
