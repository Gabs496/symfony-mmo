<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128143242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B1235E237E06 ON data_player_character (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_30B3F65F89329D2553C55F64 ON game_map_resource (resource_id, map_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B0D0B1235E237E06 ON data_player_character');
        $this->addSql('DROP INDEX UNIQ_30B3F65F89329D2553C55F64 ON game_map_resource');
    }
}
