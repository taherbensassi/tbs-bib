<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429222819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_site_package DROP FOREIGN KEY FK_EBB9775219EB6921');
        $this->addSql('DROP INDEX IDX_EBB9775219EB6921 ON tbs_site_package');
        $this->addSql('ALTER TABLE tbs_site_package DROP client_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_site_package ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE tbs_site_package ADD CONSTRAINT FK_EBB9775219EB6921 FOREIGN KEY (client_id) REFERENCES tbs_client (id)');
        $this->addSql('CREATE INDEX IDX_EBB9775219EB6921 ON tbs_site_package (client_id)');
    }
}
