<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317235725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character ADD equipment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123517FE9FE FOREIGN KEY (equipment_id) REFERENCES data_item_bag (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B123517FE9FE ON data_player_character (equipment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123517FE9FE');
        $this->addSql('DROP INDEX UNIQ_B0D0B123517FE9FE ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP equipment_id');
    }
}
