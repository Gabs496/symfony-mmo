<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221125138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_game_object CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE data_player_character ADD game_object_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', DROP health');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B123F18B9729 ON data_player_character (game_object_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123F18B9729');
        $this->addSql('DROP INDEX UNIQ_B0D0B123F18B9729 ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character ADD health JSON NOT NULL COMMENT \'(DC2Type:json_document)\', DROP game_object_id');
        $this->addSql('ALTER TABLE core_game_object CHANGE type type VARCHAR(50) NOT NULL');
    }
}
