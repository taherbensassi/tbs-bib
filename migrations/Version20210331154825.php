<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331154825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_site_package ADD user_id INT NOT NULL, DROP user');
        $this->addSql('ALTER TABLE tbs_site_package ADD CONSTRAINT FK_EBB97752A76ED395 FOREIGN KEY (user_id) REFERENCES tbs_user (id)');
        $this->addSql('CREATE INDEX IDX_EBB97752A76ED395 ON tbs_site_package (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_site_package DROP FOREIGN KEY FK_EBB97752A76ED395');
        $this->addSql('DROP INDEX IDX_EBB97752A76ED395 ON tbs_site_package');
        $this->addSql('ALTER TABLE tbs_site_package ADD user VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP user_id');
    }
}
