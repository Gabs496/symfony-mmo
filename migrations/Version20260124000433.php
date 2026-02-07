<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260124000433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position_component DROP FOREIGN KEY FK_1CDFA02ADA6A219');
        $this->addSql('ALTER TABLE place_component DROP FOREIGN KEY FK_3D65282AF18B9729');
        $this->addSql('DROP TABLE place_component');
        $this->addSql('DROP INDEX IDX_1CDFA02ADA6A219 ON position_component');
        $this->addSql('ALTER TABLE position_component ADD place_type VARCHAR(50) NOT NULL, CHANGE place_id place_id VARCHAR(100) NOT NULL');
        $this->addSql('CREATE INDEX IDX_1CDFA02A466B27C5DA6A219 ON position_component (place_type, place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE place_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_3D65282AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE place_component ADD CONSTRAINT FK_3D65282AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX IDX_1CDFA02A466B27C5DA6A219 ON position_component');
        $this->addSql('ALTER TABLE position_component DROP place_type, CHANGE place_id place_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE position_component ADD CONSTRAINT FK_1CDFA02ADA6A219 FOREIGN KEY (place_id) REFERENCES place_component (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1CDFA02ADA6A219 ON position_component (place_id)');
    }
}
