<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229215941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C0053C55F64');
        $this->addSql('DROP INDEX IDX_4478C0053C55F64 ON data_map_available_activity');
        $this->addSql('ALTER TABLE data_map_available_activity CHANGE map_id map_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123DD842E46');
        $this->addSql('DROP INDEX IDX_B0D0B123DD842E46 ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character CHANGE position_id position VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F53C55F64');
        $this->addSql('DROP INDEX IDX_30B3F65F53C55F64 ON game_map_resource');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character CHANGE position position_id VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123DD842E46 FOREIGN KEY (position_id) REFERENCES game_map (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B0D0B123DD842E46 ON data_player_character (position_id)');
        $this->addSql('ALTER TABLE data_map_available_activity CHANGE map_id map_id VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C0053C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4478C0053C55F64 ON data_map_available_activity (map_id)');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F53C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_30B3F65F53C55F64 ON game_map_resource (map_id)');
    }
}
