<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112173439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character ADD current_activity_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B1233F14CB4F FOREIGN KEY (current_activity_id) REFERENCES data_activity (id)');
        $this->addSql('CREATE INDEX IDX_B0D0B1233F14CB4F ON data_player_character (current_activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B1233F14CB4F');
        $this->addSql('DROP INDEX IDX_B0D0B1233F14CB4F ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP current_activity_id');
    }
}
