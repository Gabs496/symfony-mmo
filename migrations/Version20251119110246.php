<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119110246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_game_object CHANGE type type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE item_item_object RENAME INDEX uniq_d1948169f18b9729 TO UNIQ_CE3C8615F18B9729');
        $this->addSql('ALTER TABLE item_item_object RENAME INDEX idx_d19481696f5d8297 TO IDX_CE3C86156F5D8297');
        $this->addSql('ALTER TABLE map_map_object RENAME INDEX uniq_2f5b73df18b9729 TO UNIQ_F95635DEF18B9729');
        $this->addSql('ALTER TABLE map_map_object RENAME INDEX idx_2f5b73d53c55f64 TO IDX_F95635DE53C55F64');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_game_object CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE map_map_object RENAME INDEX uniq_f95635def18b9729 TO UNIQ_2F5B73DF18B9729');
        $this->addSql('ALTER TABLE map_map_object RENAME INDEX idx_f95635de53c55f64 TO IDX_2F5B73D53C55F64');
        $this->addSql('ALTER TABLE item_item_object RENAME INDEX idx_ce3c86156f5d8297 TO IDX_D19481696F5D8297');
        $this->addSql('ALTER TABLE item_item_object RENAME INDEX uniq_ce3c8615f18b9729 TO UNIQ_D1948169F18B9729');
    }
}
