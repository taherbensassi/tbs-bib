<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210515162111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_module ADD typo_script_code LONGTEXT NOT NULL, ADD tt_content_code LONGTEXT NOT NULL, ADD sql_override_code LONGTEXT NOT NULL, ADD sql_new_table_code LONGTEXT NOT NULL, ADD backend_preview_code LONGTEXT NOT NULL, ADD html_code LONGTEXT NOT NULL, CHANGE ts_config ts_config_code LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_module ADD ts_config LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP ts_config_code, DROP typo_script_code, DROP tt_content_code, DROP sql_override_code, DROP sql_new_table_code, DROP backend_preview_code, DROP html_code');
    }
}
