<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117021624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', coordinate_x DOUBLE PRECISION NOT NULL, coordinate_y DOUBLE PRECISION NOT NULL, spawns JSON NOT NULL, UNIQUE INDEX UNIQ_EC96E60AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE map_component ADD CONSTRAINT FK_EC96E60AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE map_component DROP FOREIGN KEY FK_EC96E60AF18B9729');
        $this->addSql('DROP TABLE map_component');
    }
}
