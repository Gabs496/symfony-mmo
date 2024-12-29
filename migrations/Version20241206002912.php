<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206002912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_available_activity ADD involving_activity_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C009477FFAB FOREIGN KEY (involving_activity_id) REFERENCES data_activity (id)');
        $this->addSql('CREATE INDEX IDX_4478C009477FFAB ON data_map_available_activity (involving_activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C009477FFAB');
        $this->addSql('DROP INDEX IDX_4478C009477FFAB ON data_map_available_activity');
        $this->addSql('ALTER TABLE data_map_available_activity DROP involving_activity_id');
    }
}
