<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229170427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', max_health DOUBLE PRECISION NOT NULL, health DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_CEAE036CF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_component ADD CONSTRAINT FK_CEAE036CF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE core_game_object DROP components');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_component DROP FOREIGN KEY FK_CEAE036CF18B9729');
        $this->addSql('DROP TABLE character_component');
        $this->addSql('ALTER TABLE core_game_object ADD components JSON NOT NULL COMMENT \'(DC2Type:json_document)\'');
    }
}
