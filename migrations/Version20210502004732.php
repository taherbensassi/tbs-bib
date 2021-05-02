<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502004732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_files DROP FOREIGN KEY FK_39C7E661569A09B5');
        $this->addSql('DROP INDEX IDX_39C7E661569A09B5 ON tbs_files');
        $this->addSql('ALTER TABLE tbs_files ADD image_name VARCHAR(255) NOT NULL, ADD image_size VARCHAR(255) DEFAULT NULL, DROP path, DROP name, DROP size, CHANGE tbs_module_image_id module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbs_files ADD CONSTRAINT FK_39C7E661AFC2B591 FOREIGN KEY (module_id) REFERENCES tbs_module (id)');
        $this->addSql('CREATE INDEX IDX_39C7E661AFC2B591 ON tbs_files (module_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbs_files DROP FOREIGN KEY FK_39C7E661AFC2B591');
        $this->addSql('DROP INDEX IDX_39C7E661AFC2B591 ON tbs_files');
        $this->addSql('ALTER TABLE tbs_files ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD size INT NOT NULL, DROP image_size, CHANGE module_id tbs_module_image_id INT DEFAULT NULL, CHANGE image_name path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tbs_files ADD CONSTRAINT FK_39C7E661569A09B5 FOREIGN KEY (tbs_module_image_id) REFERENCES tbs_module (id)');
        $this->addSql('CREATE INDEX IDX_39C7E661569A09B5 ON tbs_files (tbs_module_image_id)');
    }
}
