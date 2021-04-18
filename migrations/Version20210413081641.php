<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413081641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbs_content_elements (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, element_key VARCHAR(255) NOT NULL, element_title VARCHAR(255) NOT NULL, short_title VARCHAR(255) NOT NULL, discription VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, form_data VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_C4FF97B0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbs_content_elements ADD CONSTRAINT FK_C4FF97B0A76ED395 FOREIGN KEY (user_id) REFERENCES tbs_user (id)');
        $this->addSql('ALTER TABLE tbs_client CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbs_content_elements');
        $this->addSql('ALTER TABLE tbs_client CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
