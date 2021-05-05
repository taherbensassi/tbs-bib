<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503002605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_module CHANGE user_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE tbs_module ADD CONSTRAINT FK_24196C10F675F31B FOREIGN KEY (author_id) REFERENCES tbs_user (id)');
        $this->addSql('CREATE INDEX IDX_24196C10F675F31B ON tbs_module (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_module DROP FOREIGN KEY FK_24196C10F675F31B');
        $this->addSql('DROP INDEX IDX_24196C10F675F31B ON tbs_module');
        $this->addSql('ALTER TABLE tbs_module CHANGE author_id user_id INT NOT NULL');
    }
}
