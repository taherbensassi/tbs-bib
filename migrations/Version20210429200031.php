<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429200031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_client ADD deleted TINYINT(1) NOT NULL, ADD hidden TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tbs_content_elements ADD deleted TINYINT(1) NOT NULL, ADD hidden TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tbs_site_package ADD deleted TINYINT(1) NOT NULL, ADD hidden TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tbs_user ADD hidden TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_client DROP deleted, DROP hidden');
        $this->addSql('ALTER TABLE tbs_content_elements DROP deleted, DROP hidden');
        $this->addSql('ALTER TABLE tbs_site_package DROP deleted, DROP hidden');
        $this->addSql('ALTER TABLE tbs_user DROP hidden');
    }
}
