<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128150843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E59C60354');
        $this->addSql('ALTER TABLE data_mastery_skill DROP FOREIGN KEY FK_78CAB74959C60354');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59C60354');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2E59C60354');
        $this->addSql('CREATE TABLE data_mastery (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE data_mastery_collection');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id)');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id)');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id)');
        $this->addSql('ALTER TABLE data_mastery_skill ADD CONSTRAINT FK_78CAB74959C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id)');
        $this->addSql('ALTER TABLE data_player_character ADD mastery_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123E26FCE68 FOREIGN KEY (mastery_id) REFERENCES data_mastery (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B123E26FCE68 ON data_player_character (mastery_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E59C60354');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2E59C60354');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59C60354');
        $this->addSql('ALTER TABLE data_mastery_skill DROP FOREIGN KEY FK_78CAB74959C60354');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123E26FCE68');
        $this->addSql('CREATE TABLE data_mastery_collection (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE data_mastery');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_skill ADD CONSTRAINT FK_78CAB74959C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX UNIQ_B0D0B123E26FCE68 ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP mastery_id');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
