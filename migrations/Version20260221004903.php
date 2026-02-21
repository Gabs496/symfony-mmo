<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260221004903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE placed_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', place_type VARCHAR(50) NOT NULL, place_id VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_21E95A1AF18B9729 (game_object_id), INDEX IDX_21E95A1A466B27C5DA6A219 (place_type, place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE placed_component ADD CONSTRAINT FK_21E95A1AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE position_component DROP FOREIGN KEY FK_1CDFA02AF18B9729');
        $this->addSql('DROP TABLE position_component');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', place_id VARCHAR(100) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, position VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, place_type VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_1CDFA02AF18B9729 (game_object_id), INDEX IDX_1CDFA02A466B27C5DA6A219 (place_type, place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE position_component ADD CONSTRAINT FK_1CDFA02AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE placed_component DROP FOREIGN KEY FK_21E95A1AF18B9729');
        $this->addSql('DROP TABLE placed_component');
    }
}
