<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316035019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_map_spawned_resource (id VARCHAR(255) NOT NULL, involving_activity_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) NOT NULL, resource_id VARCHAR(50) NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_1997376E9477FFAB (involving_activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_map_spawned_resource ADD CONSTRAINT FK_1997376E9477FFAB FOREIGN KEY (involving_activity_id) REFERENCES data_activity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_map_spawned_resource DROP FOREIGN KEY FK_1997376E9477FFAB');
        $this->addSql('DROP TABLE game_map_spawned_resource');
    }
}
